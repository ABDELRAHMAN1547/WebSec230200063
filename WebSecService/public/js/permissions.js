// Permissions Management JavaScript

// CSRF Token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Set up AJAX headers
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
});

// Create Role Form Handler
$('#createRoleForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const permissions = [];
    
    // Collect selected permissions
    $('input[name="permissions[]"]:checked').each(function() {
        permissions.push($(this).val());
    });
    
    const data = {
        name: formData.get('name'),
        display_name: formData.get('display_name'),
        description: formData.get('description'),
        permissions: permissions
    };
    
    $.ajax({
        url: '/admin/permissions/roles',
        method: 'POST',
        data: data,
        success: function(response) {
            showAlert('success', response.success);
            $('#createRoleModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'حدث خطأ غير متوقع';
            showAlert('danger', error);
        }
    });
});

// Edit Role Function
function editRole(roleId) {
    $.ajax({
        url: `/admin/permissions/roles/${roleId}`,
        method: 'GET',
        success: function(role) {
            $('#edit_role_id').val(role.id);
            $('#edit_role_name').val(role.name);
            $('#edit_role_display_name').val(role.display_name);
            $('#edit_role_description').val(role.description);
            
            // Populate permissions checkboxes
            populateEditPermissions(role.permissions);
            
            $('#editRoleModal').modal('show');
        },
        error: function(xhr) {
            showAlert('danger', 'فشل في تحميل بيانات الدور');
        }
    });
}

// Populate Edit Permissions
function populateEditPermissions(rolePermissions) {
    const container = $('#edit_permissions_container');
    container.empty();
    
    // Get all permissions grouped by module
    $.ajax({
        url: '/admin/permissions/all',
        method: 'GET',
        success: function(permissions) {
            Object.keys(permissions).forEach(module => {
                const moduleDiv = $(`
                    <div class="mb-3">
                        <h6 class="text-primary">${module}</h6>
                        <div class="row" id="module_${module}">
                        </div>
                    </div>
                `);
                
                permissions[module].forEach(permission => {
                    const isChecked = rolePermissions.some(rp => rp.id === permission.id);
                    const permissionDiv = $(`
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[]" value="${permission.id}" 
                                       id="edit_perm_${permission.id}" ${isChecked ? 'checked' : ''}>
                                <label class="form-check-label" for="edit_perm_${permission.id}">
                                    ${permission.display_name}
                                </label>
                            </div>
                        </div>
                    `);
                    moduleDiv.find(`#module_${module}`).append(permissionDiv);
                });
                
                container.append(moduleDiv);
            });
        }
    });
}

// Edit Role Form Handler
$('#editRoleForm').on('submit', function(e) {
    e.preventDefault();
    
    const roleId = $('#edit_role_id').val();
    const formData = new FormData(this);
    const permissions = [];
    
    // Collect selected permissions
    $('#edit_permissions_container input[name="permissions[]"]:checked').each(function() {
        permissions.push($(this).val());
    });
    
    const data = {
        display_name: formData.get('display_name'),
        description: formData.get('description'),
        permissions: permissions
    };
    
    $.ajax({
        url: `/admin/permissions/roles/${roleId}`,
        method: 'PUT',
        data: data,
        success: function(response) {
            showAlert('success', response.success);
            $('#editRoleModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'حدث خطأ غير متوقع';
            showAlert('danger', error);
        }
    });
});

// Delete Role Function
function deleteRole(roleId) {
    if (confirm('هل أنت متأكد من حذف هذا الدور؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        $.ajax({
            url: `/admin/permissions/roles/${roleId}`,
            method: 'DELETE',
            success: function(response) {
                showAlert('success', response.success);
                location.reload();
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'حدث خطأ غير متوقع';
                showAlert('danger', error);
            }
        });
    }
}

// View Role Function
function viewRole(roleId) {
    $.ajax({
        url: `/admin/permissions/roles/${roleId}/details`,
        method: 'GET',
        success: function(data) {
            $('#view_role_name').text(data.role.name);
            $('#view_role_display_name').text(data.role.display_name);
            $('#view_role_description').text(data.role.description || 'لا يوجد وصف');
            
            // Display permissions
            const permissionsContainer = $('#view_role_permissions');
            permissionsContainer.empty();
            
            if (data.role.permissions.length > 0) {
                data.role.permissions.forEach(permission => {
                    permissionsContainer.append(`
                        <span class="badge bg-primary me-1 mb-1">${permission.display_name}</span>
                    `);
                });
            } else {
                permissionsContainer.append('<p class="text-muted">لا توجد صلاحيات مرتبطة</p>');
            }
            
            // Display users
            const usersContainer = $('#view_role_users');
            usersContainer.empty();
            
            if (data.role.users.length > 0) {
                data.role.users.forEach(user => {
                    usersContainer.append(`
                        <span class="badge bg-info me-1 mb-1">${user.name}</span>
                    `);
                });
            } else {
                usersContainer.append('<p class="text-muted">لا يوجد مستخدمين مرتبطين</p>');
            }
            
            $('#viewRoleModal').modal('show');
        },
        error: function(xhr) {
            showAlert('danger', 'فشل في تحميل تفاصيل الدور');
        }
    });
}

// Create Permission Form Handler
$('#createPermissionForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        display_name: formData.get('display_name'),
        description: formData.get('description'),
        module: formData.get('module'),
        action: formData.get('action')
    };
    
    $.ajax({
        url: '/admin/permissions/permissions',
        method: 'POST',
        data: data,
        success: function(response) {
            showAlert('success', response.success);
            $('#createPermissionModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'حدث خطأ غير متوقع';
            showAlert('danger', error);
        }
    });
});

// Delete Permission Function
function deletePermission(permissionId) {
    if (confirm('هل أنت متأكد من حذف هذه الصلاحية؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        $.ajax({
            url: `/admin/permissions/permissions/${permissionId}`,
            method: 'DELETE',
            success: function(response) {
                showAlert('success', response.success);
                location.reload();
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.error || 'حدث خطأ غير متوقع';
                showAlert('danger', error);
            }
        });
    }
}

// Manage User Roles Function
function manageUserRoles(userId) {
    $.ajax({
        url: `/admin/permissions/users/${userId}/roles`,
        method: 'GET',
        success: function(data) {
            $('#manage_user_id').val(data.user.id);
            $('#manage_user_name').text(data.user.name);
            $('#manage_user_email').text(data.user.email);
            
            // Populate available roles
            const container = $('#available_roles_container');
            container.empty();
            
            data.available_roles.forEach(role => {
                const isChecked = data.user.roles.some(ur => ur.id === role.id);
                const roleDiv = $(`
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="role_ids[]" value="${role.id}" 
                               id="role_${role.id}" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="role_${role.id}">
                            ${role.display_name}
                        </label>
                    </div>
                `);
                container.append(roleDiv);
            });
            
            $('#manageUserRolesModal').modal('show');
        },
        error: function(xhr) {
            showAlert('danger', 'فشل في تحميل بيانات المستخدم');
        }
    });
}

// Manage User Roles Form Handler
$('#manageUserRolesForm').on('submit', function(e) {
    e.preventDefault();
    
    const userId = $('#manage_user_id').val();
    const roleIds = [];
    
    // Collect selected roles
    $('input[name="role_ids[]"]:checked').each(function() {
        roleIds.push($(this).val());
    });
    
    const data = {
        user_id: userId,
        role_ids: roleIds
    };
    
    $.ajax({
        url: '/admin/permissions/assign-role',
        method: 'POST',
        data: data,
        success: function(response) {
            showAlert('success', response.success);
            $('#manageUserRolesModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'حدث خطأ غير متوقع';
            showAlert('danger', error);
        }
    });
});

// Show Alert Function
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top of the page
    $('body').prepend(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// Auto-generate permission name based on module and action
$('#createPermissionModal select[name="module"], #createPermissionModal select[name="action"]').on('change', function() {
    const module = $('select[name="module"]').val();
    const action = $('select[name="action"]').val();
    
    if (module && action) {
        $('input[name="name"]').val(`${module}.${action}`);
    }
});

// Initialize tooltips
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
