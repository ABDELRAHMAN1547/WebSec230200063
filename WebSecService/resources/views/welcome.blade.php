@extends('layouts.app')

@section('title', 'الصفحة الرئيسية - WebSecService')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        مرحباً بك في WebSecService
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">إدارة الطلاب</h5>
                                    <p class="card-text">إضافة وتعديل وحذف بيانات الطلاب</p>
                                    <a href="{{ url('/students') }}" class="btn btn-primary">إدارة الطلاب</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-cog fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">إدارة المستخدمين</h5>
                                    <p class="card-text">إدارة حسابات المستخدمين والصلاحيات</p>
                                    <a href="{{ url('/users') }}" class="btn btn-success">إدارة المستخدمين</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-graduation-cap fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">إدارة الدرجات</h5>
                                    <p class="card-text">تسجيل وإدارة درجات الطلاب</p>
                                    <a href="{{ url('/grades') }}" class="btn btn-info">إدارة الدرجات</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-tachometer-alt fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title">لوحة التحكم</h5>
                                    <p class="card-text">عرض إحصائيات وملخص النظام</p>
                                    <a href="{{ route('dashboard') }}" class="btn btn-warning">لوحة التحكم</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
