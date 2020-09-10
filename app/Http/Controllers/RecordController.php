<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\Category;
use App\Models\Record;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecordController extends Controller
{
    public function batch()
    {
        $this->vld([
            'appId' => 'required|exists:apps,id',
            'json' => 'json|required_without:file',
            'file' => 'file|required_without:json',
        ]);
        $records = [];
        request('json') && $records = json_decode(request('json'));
        request('file') && $records = json_decode(file_get_contents(request()->file('file')->getRealPath()), true);
        $app = App::query()->find(request('appId'));
        $method = 'handle' . ucfirst($app->key);
        return $this->$method($app, $records);
    }

    public function week()
    {
        $this->vld(['year' => 'date_format:"Y"',]);
        $year = request('year', date('Y'));
        $begin = Carbon::create($year);
        $end = $year == date('Y') ? Carbon::now() : Carbon::create($year)->lastOfYear();
        $earns = Record::query()->in()->get();
        $previousCost = round(Record::query()->where('created_at', '<', $begin)->out()->sum('number'));
        $records = Record::query()
            ->whereBetween('created_at', [$begin, $end])
            ->selectRaw('round(sum(number)) as number, weekofyear(created_at) as week')
            ->groupByRaw('week')
            ->out()
            ->get();
        $records = $records->map(function ($r) use ($year, $earns, & $previousCost) {
            $r->date = Carbon::create($year)->addWeeks($r->week);
            $earn = $earns->where('created_at', '<', $r->date)->sum('number');
            $r->remain = $earn + $previousCost;
            $previousCost += $r->number;
            return $r;
        })->groupBy(function($r) {
            return $r->date->month;
        });
        $monthRecords = [];
        foreach ($records as $month => $monthRecordList) {
            $monthRecords[] = [
                'month' => $month,
                'number' => $monthRecordList->sum('number'),
                'remain' => $monthRecordList->last()->remain,
                'weeks' => $monthRecordList,
            ];
        }
        $list = collect();
        foreach ($monthRecords as $monthRecord) {
            foreach ($monthRecord['weeks'] as $weekRecord) {
                $weekRecord->month = Arr::only($monthRecord, ['month', 'number', 'remain']);
                $weekRecord->date = $weekRecord->date->format('n / d');
                $list->push($weekRecord);
            }
        }
        $list = $list->sort(function($a, $b) { return $b->week - $a->week; })->values();
        return view('record/week', compact('list', 'year'));
    }

    private function handleQianji($app, $records)
    {
        $rules = [
            'key' => 'required',
            'date' => 'required|date',
            'category' => 'required',
            'type' => 'required|in:收入,支出',
            'money' => 'required|numeric',
        ];
        foreach ($records as $record) {
            $vld = Validator::make($record, $rules);
            if ($vld->fails()) {
                return $this->backErr($vld->errors()->first());
            }
        }
        $recordKeys = array_column($records, 'key');
        $existedRecordKeys = Record::query()->where('app_id', $app->id)->whereIn('app_record_id', $recordKeys)->pluck('app_record_id');
        $toAddRecords = collect($records)->sort(function($a, $b) { return strtotime($a['date']) - strtotime($b['date']); })->whereNotIn('key', $existedRecordKeys->values());
        foreach ($toAddRecords as $record) {
            try {
                Record::query()->create([
                    'appId' => $app->id,
                    'appRecordId' => $record['key'],
                    'categoryId' => $this->categoryId($record['category']),
                    'number' => $record['type'] == '支出' ? -$record['money'] : $record['money'],
                    'createdAt' => $record['date'],
                ]);
            } catch (\Exception $e) {
                return $this->backErr($e->getMessage());
            }
        }
        return $this->backOk();
    }

    private function categoryId($name)
    {
        $category = Category::query()->where('name', $name)->first();
        if ($category) {
            return $category->id;
        }
        DB::beginTransaction();
        try {
            Category::query()->lockForUpdate();
            $category = new Category();
            $category->name = $name;
            $category->position = Category::query()->max('position') + 1;
            $category->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $category->id;
    }
}
