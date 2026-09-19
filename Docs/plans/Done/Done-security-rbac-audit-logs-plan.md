# NCS System Security, RBAC & Audit Logs Module Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/security-rbac-audit-logs-plan.md`  
> **Role:** Systems Administrator / Security Officer / Internal Auditor / Super Admin  
> **System Scope:** System Security Controls & Governance (`SecuritySettingsView.vue`, `UsersView.vue`, `RolesView.vue`, `AuditLogsView.vue`)  
> **Authority Scope:** Role-Based Access Control (RBAC), Multi-Factor Authentication (2FA), Session Governance, Immutable System Security Logs, Password Policies, Security Threat Alerts  
> **Compliance Standards:** Computer Misuse Act (Uganda), ISO 27001 Information Security Management Standard  

---

## 1. Executive Purpose & Security Architecture

The **System Security, RBAC & Audit Logs Module** forms the cybersecurity backbone of the National Council of Sports Intranet. It enforces strict Role-Based Access Control (RBAC), Multi-Factor Authentication (2FA), encrypted user credential management, and immutable audit logging tracking every security event and data modification across the intranet.

```
+-----------------------------------------------------------------------------------+
|                        NCS SYSTEM SECURITY & GOVERNANCE ARCHITECTURE              |
+-----------------------------------------------------------------------------------+
       |                                  |                                 |
       v                                  v                                 v
+-----------------------+      +-----------------------+      +---------------------+
| 1. USER & RBAC CONTROL|      | 2. SECURITY & 2FA     |      | 3. IMMUTABLE SYSTEM |
| (`UsersView`, `Roles`)|      | (`SecuritySettings`)  |      |    AUDIT LOG ENGINE |
+-----------------------+      +-----------------------+      +---------------------+
| - Role Assignments    |      | - TOTP / SMS 2FA      |      | - User Action Logs  |
| - Department Scoping  |      | - Password Hygiene    |      | - Login History     |
| - Tenancy Isolation   |      | - Active Session Kill |      | - Export Security Log|
+-----------------------+      +-----------------------+      +---------------------+
```

---

## 2. Key Modules & User Interface Specifications

### 2.1 User & Role-Based Access Management (`UsersView.vue`, `RolesView.vue`)
- **Fine-Grained Role Permissions:** Granting granular capabilities across roles (`ncs_general_secretary`, `cfo`, `accountant`, `hr_manager`, `internal_auditor`, `sys_admin`, `technical_director`, `procurement_manager`, `senior_engineer`, `staff`).
- **Departmental Scoping & Tenancy Isolation:** Restricting data edit rights strictly to assigned departments while enabling executive oversight.

### 2.2 Security Settings & 2FA Enforcement (`SecuritySettingsView.vue`)
- **Two-Factor Authentication (2FA):** Time-based One-Time Password (TOTP) authenticator app integration and emergency backup codes.
- **Active Sessions Controller:** View all active user login sessions (IP address, browser, device, login timestamp) with one-click remote session termination.

### 2.3 System Audit Trail & Event Monitoring (`AuditLogsView.vue`)
- **Immutable Log Engine:** Records user ID, IP address, action type (CREATE, READ, UPDATE, DELETE, LOGIN, EXPORT), resource affected, timestamp, and previous vs new value diffs.

---

## 3. Database Schema & REST API Mapping

```sql
CREATE TABLE audit_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    actor_id UUID REFERENCES users(id),
    actor_email VARCHAR(128) NOT NULL,
    action_type VARCHAR(64) NOT NULL, -- USER_LOGIN, ROLE_CHANGE, ASSET_ADJUST, PAYROLL_APPROVE
    resource_name VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NOT NULL,
    details JSONB NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
```

| Endpoint | Method | Scope | Function |
| :--- | :--- | :--- | :--- |
| `/api/v1/security/users` | GET/POST/PUT | Admin, SysAdmin | Manage user accounts and role assignments |
| `/api/v1/security/2fa/enable` | POST | All Staff | Enable Two-Factor Authentication (TOTP) |
| `/api/v1/audit/logs` | GET | Auditor, Admin | Query immutable system audit logs |
