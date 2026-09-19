# NCS Online & Intranet - Podman Setup & Role Testing Guide

This guide provides instructions for setting up, building, and running the National Council of Sports (NCS) Intranet platform using **Podman** instead of Docker, and verifying every organizational use case using the test accounts in [`Docs/Credentials.csv`](file:///d:/NCSOnline/Docs/Credentials.csv).

---

## 1. Prerequisites & Podman Installation

### On Windows
1. Install Podman via **Winget** or direct installer:
   ```powershell
   winget install RedHat.Podman
   ```
2. Initialize and start the Podman virtual machine (WSL2-backed):
   ```powershell
   podman machine init
   podman machine start
   ```
3. Verify installation:
   ```powershell
   podman --version
   ```

### On Linux (Ubuntu / Debian / Fedora)
```bash
sudo apt update && sudo apt install -y podman podman-compose
# Or for Fedora / RHEL:
sudo dnf install -y podman podman-compose
```

---

## 2. Quick Start with Podman

### Option A: Automated PowerShell Script (Windows)
```powershell
.\podman-setup.ps1
```

### Option B: Automated Shell Script (Linux / WSL)
```bash
chmod +x podman-setup.sh
./podman-setup.sh
```

### Option C: Direct Podman Compose CLI
```bash
# Build and start all containers in background
podman compose -f podman-compose.yml up -d --build

# Or if using podman-compose python utility:
podman-compose -f podman-compose.yml up -d --build
```

---

## 3. Container Services & Port Mappings

| Service Name | Container Name | Internal Port | Host Port | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| **Nginx Reverse Proxy** | `ncs_nginx` | 80 / 443 | `9081` / `9444` | Frontend delivery, API proxying, SSL |
| **Backend Go API** | `ncs_backend` | 8080 | Internal | REST API, Business Logic, Auth |
| **PostgreSQL 16** | `ncs_postgres` | 5432 | `5436` | Database with automatic migration runner |
| **Location Service** | `ncs_location_service` | 8090 | Internal | Geo-IP validation & national IP filter |

- **Frontend / Portal Access:** [http://localhost:9081](http://localhost:9081)
- **Health Check Endpoint:** [http://localhost:9081/healthz](http://localhost:9081/healthz)

---

## 4. Role Testing Matrix & Credentials

All test accounts are automatically seeded into PostgreSQL via migration `073_seed_all_departmental_users.sql`. The complete dataset is available in [`Docs/Credentials.csv`](file:///d:/NCSOnline/Docs/Credentials.csv).

| Role Key | Designation | Login Email | Default Password | Primary Dashboard Route |
| :--- | :--- | :--- | :--- | :--- |
| `super_admin` | System Administrator | `admin@ncs.go.ug` | `NCS@Admin2026!` | `/dashboard` |
| `general_secretary` | General Secretary (Accounting Officer) | `gs@ncs.go.ug` | `NCS@Executive2026!` | `/executive/appraisals` |
| `ags_technical` | Assistant General Secretary - Technical | `agst@ncs.go.ug` | `NCS@Technical2026!` | `/executive/ags-technical` |
| `ags_admin` | Assistant General Secretary - Administration | `agsa@ncs.go.ug` | `NCS@Admin2026!` | `/executive/ags-admin` |
| `technical_department` | Technical Director | `technical@ncs.go.ug` | `NCS@Sports2026!` | `/nsmis/governance` |
| `senior_engineer` | Senior Infrastructure Engineer | `seniorengineer@ncs.go.ug` | `NCS@Eng2026!` | `/maintenance/command-center` |
| `assistant_engineer_civil` | Assistant Engineer (Civil) | `civil.engineer@ncs.go.ug` | `NCS@Civil2026!` | `/maintenance` |
| `assistant_engineer_electrical` | Assistant Engineer (Electrical) | `electrical.engineer@ncs.go.ug` | `NCS@Electro2026!` | `/maintenance` |
| `engineering_officer_civil` | Civil Engineering Officer | `civil.officer@ncs.go.ug` | `NCS@Works2026!` | `/maintenance` |
| `engineering_officer_electrical` | Electrical Engineering Officer | `electrical.officer@ncs.go.ug` | `NCS@Power2026!` | `/maintenance` |
| `plumber` | Plumber & Water Infrastructure Lead | `plumber@ncs.go.ug` | `NCS@Plumber2026!` | `/maintenance` |
| `accountant` | Senior Accountant / CFO | `accountant@ncs.go.ug` | `NCS@Finance2026!` | `/dashboard` |
| `auditor` | Head of Internal Audit | `auditor@ncs.go.ug` | `NCS@Audit2026!` | `/audit-logs` |
| `human_resources` | Human Resources Manager | `hr@ncs.go.ug` | `NCS@HR2026!` | `/users` |
| `helpdesk` | IT Service Desk Lead | `helpdesk@ncs.go.ug` | `NCS@Helpdesk2026!` | `/maintenance` |
| `it_officer` | ICT Systems Administrator | `itofficer@ncs.go.ug` | `NCS@IT2026!` | `/maintenance/backups` |
| `procurement_officer` | Head of PDU | `procurement@ncs.go.ug` | `NCS@PDU2026!` | `/applications` |
| `public_relations` | PR & Communications Officer | `pr@ncs.go.ug` | `NCS@Media2026!` | `/cms` |
| `stores_officer` | Stores Officer / Custodian | `stores@ncs.go.ug` | `NCS@Stores2026!` | `/stores/inventory` |
| `facilities_manager` | Facilities & Venue Operations | `facilities@ncs.go.ug` | `NCS@Venues2026!` | `/facilities/venues` |
| `legal_counsel` | Legal Counsel & Compliance | `legal@ncs.go.ug` | `NCS@Legal2026!` | `/legal/compliance` |
| `medical_officer` | Chief Medical Officer | `medical@ncs.go.ug` | `NCS@Medical2026!` | `/medical/sports-science` |
| `physiotherapist` | Senior Physiotherapist | `physio@ncs.go.ug` | `NCS@Physio2026!` | `/medical/sports-science` |
| `transport_officer` | Transport Officer / Fleet Manager | `transport@ncs.go.ug` | `NCS@Fleet2026!` | `/fleet/transport` |
| `driver` | Official Council Driver | `driver@ncs.go.ug` | `NCS@Driver2026!` | `/fleet/transport` |
| `federation_president` | National Federation President | `federation.president@ncs.go.ug` | `NCS@Federation2026!` | `/nsmis/governance` |
| `federation_general_secretary` | Federation General Secretary | `federation.gs@ncs.go.ug` | `NCS@FedGS2026!` | `/nsmis/reports` |
| `safeguarding_officer` | Athlete Safeguarding Officer | `safeguarding@ncs.go.ug` | `NCS@Safeguard2026!` | `/nsmis/data/safeguarding-aggregates` |
| `content_manager` | CMS Content Editor | `content@ncs.go.ug` | `NCS@CMS2026!` | `/cms` |
| `user` | Athlete / Public Applicant | `applicant@ncs.go.ug` | `NCS@Applicant2026!` | `/my-portal` |

---

## 5. Useful Podman Commands

- **Check container status:**
  ```bash
  podman ps
  ```
- **View backend logs:**
  ```bash
  podman logs -f ncs_backend
  ```
- **View Nginx access logs:**
  ```bash
  podman logs -f ncs_nginx
  ```
- **Stop stack:**
  ```bash
  podman compose -f podman-compose.yml down
  ```
- **Restart stack:**
  ```bash
  podman compose -f podman-compose.yml restart
  ```
