<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء دور جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRoleForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">اسم الدور (بالإنجليزية)</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">الاسم المعروض</label>
                                <input type="text" class="form-control" name="display_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الصلاحيات</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($permissions as $module => $modulePermissions)
                            <div class="mb-3">
                                <h6 class="text-primary">{{ ucfirst($module) }}</h6>
                                <div class="row">
                                    @foreach($modulePermissions as $permission)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ $permission->display_name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إنشاء الدور</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل الدور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm">
                <input type="hidden" name="role_id" id="edit_role_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">اسم الدور (بالإنجليزية)</label>
                                <input type="text" class="form-control" name="name" id="edit_role_name" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">الاسم المعروض</label>
                                <input type="text" class="form-control" name="display_name" id="edit_role_display_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" name="description" id="edit_role_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الصلاحيات</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;" id="edit_permissions_container">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">تحديث الدور</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Permission Modal -->
<div class="modal fade" id="createPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء صلاحية جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createPermissionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">اسم الصلاحية (بالإنجليزية)</label>
                        <input type="text" class="form-control" name="name" required>
                        <div class="form-text">مثال: users.create</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الاسم المعروض</label>
                        <input type="text" class="form-control" name="display_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">الوحدة</label>
                                <select class="form-select" name="module" required onchange="updatePermissionName()">
                                    <option value="">اختر الوحدة</option>
                                    <option value="users">المستخدمين</option>
                                    <option value="roles">الأدوار</option>
                                    <option value="permissions">الصلاحيات</option>
                                    <option value="dashboard">لوحة التحكم</option>
                                    <option value="reports">التقارير</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">الإجراء</label>
                                <select class="form-select" name="action" required onchange="updatePermissionName()">
                                    <option value="">اختر الإجراء</option>
                                    <option value="view">عرض</option>
                                    <option value="create">إنشاء</option>
                                    <option value="edit">تعديل</option>
                                    <option value="delete">حذف</option>
                                    <option value="manage">إدارة</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">إنشاء الصلاحية</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manage User Roles Modal -->
<div class="modal fade" id="manageUserRolesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إدارة أدوار المستخدم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="manageUserRolesForm">
                <input type="hidden" name="user_id" id="manage_user_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 id="manage_user_name"></h6>
                        <small class="text-muted" id="manage_user_email"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الأدوار المتاحة</label>
                        <div id="available_roles_container">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تحديث الأدوار</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Role Modal -->
<div class="modal fade" id="viewRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">عرض تفاصيل الدور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>اسم الدور:</h6>
                        <p id="view_role_name"></p>
                    </div>
                    <div class="col-md-6">
                        <h6>الاسم المعروض:</h6>
                        <p id="view_role_display_name"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <h6>الوصف:</h6>
                    <p id="view_role_description"></p>
                </div>
                <div class="mb-3">
                    <h6>الصلاحيات:</h6>
                    <div id="view_role_permissions"></div>
                </div>
                <div class="mb-3">
                    <h6>المستخدمين المرتبطين:</h6>
                    <div id="view_role_users"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
