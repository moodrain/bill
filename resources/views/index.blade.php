@extends('layout.frame')

@section('title', 'Home')

@section('main')
    <el-row>
        <el-col :xs="24" :span="12">
            <el-card>
                <p slot="header">Batch</p>
                <el-form inline>
                    <x-select exp="model:appId;label:App;key:id;selectLabel:name;value:id;data:apps" />
                    <el-form-item>
                        <el-button @click="upload" :disabled="! appId">Upload</el-button>
                    </el-form-item>
                </el-form>
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
            menuActive: 'home',
            form: {
                appId: null,
                file: null,
            },
            appId: null,
            apps: @json($apps),
        }
    },
    mounted() {
        @include('piece.init')
        this.appId = this.apps[0].id
    },
    methods: {
        @include('piece.method')
        upload() {
            let input = document.createElement('input')
            input.type = 'file'
            input.style.display = 'none'
            input.addEventListener('change', () => {
                this.$submit('record/batch', {appId: this.appId, file: input})
            })
            input.click()
        }
    }
})
</script>
@endsection