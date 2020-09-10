@extends('layout.app')
@section('title', 'Register')
@section('html')
    <div id="app">
        <br />
        <el-row>
            <el-col :span="6" :offset="9" :xs="{span:20,offset:2}">
                <el-card>
                    <el-form>
                        <x-input exp="model:form.email;pre:Email" />
                        <x-input exp="model:form.name;pre:Name" />
                        <x-input exp="model:form.password;pre:Password;type:password" />
                        <x-input exp="model:form.rePassword;pre:RePassword;type:password" />
                        <el-form-item>
                            <el-button @click="register">Register</el-button>
                            <el-divider direction="vertical"></el-divider>
                            <el-link href="/login">or Login</el-link>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-col>
        </el-row>
    </div>
@endsection

@section('js')
    @include('layout.js')
    <script>
        let vue = new Vue({
            el: '#app',
            data() {
                return {
                    @include('piece.data')
                    form: {
                        email: '{{ old('email') }}',
                        name: '{{ old('name') }}',
                        password: '',
                        rePassword: '',
                    }
                }
            },
            methods: {
                @include('piece.method')
                register() {
                    if (! this.form.email || ! this.form.password || ! this.form.name) {
                        alert('some inputs are empty')
                        return
                    }
                    if (this.form.password != this.form.rePassword) {
                        alert('password not equal re password')
                        return
                    }
                    $submit(this.form)
                }
            },
            mounted() {
                @include('piece.init')
            }
        })
        $enter(() => vue.register())
    </script>
@endsection

@section('css')
    @include('layout.css')
@endsection