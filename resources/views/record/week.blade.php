@extends('layout.frame')

@section('title', 'Record Week')

@section('main')
    <el-row>
        <el-col :xs="24" :span="12">
            <el-card>
                <el-table :data="list">
                    <el-table-column label="Cost" prop="number"></el-table-column>
                    <el-table-column label="Remain" prop="remain"></el-table-column>
                    <el-table-column label="Week" prop="week"></el-table-column>
                    <el-table-column label="Month" prop="month.month"></el-table-column>
                    <el-table-column label="Date" prop="date"></el-table-column>
                    <el-table-column label="Month Cost" prop="month.number"></el-table-column>
                    <el-table-column label="Month Remain" prop="month.remain"></el-table-column>
                </el-table>
            </el-card>
        </el-col>
    </el-row>
@endsection

@section('script')
    <script>
        new Vue({
            el: '#app',
            data () {
                return {
                    @include('piece.data')
                    menuActive: 'record-week',
                    year: {{ $year }},
                    list: @json($list),
                }
            },
            mounted() {
                @include('piece.init')

            },
            methods: {
                @include('piece.method')
            }
        })
    </script>
@endsection