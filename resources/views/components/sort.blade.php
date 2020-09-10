<x-select exp="model:sort.prop;label:OrderBy;data:sortOptions"></x-select>
<el-form-item>
    <el-select v-model="sort.order">
        <el-option :key="'ascending'" :label="'ascending'" :value="'asc'"></el-option>
        <el-option :key="'descending'" :label="'descending'" :value="'desc'"></el-option>
    </el-select>
</el-form-item>