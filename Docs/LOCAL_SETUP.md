# NCS local installation

The application title is **National Council Of Sports**. The supplied `ncs_logo.png` is the source for the default site, invoice, favicon, and PWA assets.

- This computer: http://localhost/NCS_Intranet/
- Devices on the same Wi-Fi: http://10.25.78.105/NCS_Intranet/
- Start Apache and PostgreSQL in Laragon after restarting Windows. Keep this computer awake while other devices use the app.
- The Wi-Fi address can change; use `ipconfig` to find the current address.
- Administrator credentials are in `writable/logs/local-admin.txt`, which is excluded from Git and blocked over HTTP. Change the initial password after login.

The local PostgreSQL server initially contained no application database. `ncs_db` was created from `database_schema.sql`, populated with the bundled installer defaults, and given a new administrator account. This is a fresh installation, not a recovery of historical records.

A Windows firewall rule named `NCS-Intranet-LAN-HTTP` permits the current Laragon Apache executable on TCP port 80 from the local subnet only. `scripts/enable-lan.ps1` can recreate it from an administrator PowerShell. Other firewall software and Wi-Fi client isolation can still prevent access between devices.

Validation: PHP syntax checks, authenticated sign-in/dashboard, general settings, plugin listing, PWA manifest, title/logo references, and blocking direct access to configuration, SQL, and credential files. The user confirmed that the sign-in page opens on another device connected to the same Wi-Fi. Automated browser testing was unavailable in this session.

App-owned RISE names were updated consistently across PHP classes/files, routes, hooks, database configuration/schema, CSS/SCSS, and JavaScript events/selectors. Vendor service/documentation URLs, third-party code, and unrelated words such as `sunrise` retain their original spelling to preserve their meaning and functionality.
