## MySQL Upgrade Issue Report

**Date:** October 2025   
**Environment:** Dokku (Herokuish-based deployment)  
**Upgrade Path:** MySQL 5.7 → 8.0.35  
**Affected App:** alertme-za  

## 1. Overview

During the MySQL upgrade from version 5.7 to 8.0.35, several issues were encountered that impacted the application build and deployment process. These issues were primarily caused by configuration incompatibilities introduced by the new MySQL version and Dokku build environment behavior.

## 2. Issues Encountered
### Issue 1: Restart Failure – “No Procfile found in app image”

When attempting to restart the application:

```
ubuntu@ip-172-31-19-112:~$ dokku ps:restart alertme-za
-----> Releasing alertme-za...
-----> Deploying alertme-za...
-----> Checking for predeploy task
    No predeploy task found, skipping
-----> Checking for release task
    No release task found, skipping
-----> No Procfile found in app image
-----> DOKKU_SCALE file exists
=====> Processing deployment checks
    No CHECKS file found. Simple container checks will be performed.
    For more efficient zero downtime deployments, create a CHECKS file. See https://dokku.com/docs/deployment/zero-downtime-deploys/ for examples
!     No procfile found
2025/10/13 06:45:29 exit status 1
-----> Attempting pre-flight checks (web.1)
    Waiting for 10 seconds ...
!     App container failed to start!!
```

### Root Cause:
The application image lacked a Procfile, which defines process types for the container runtime. Without it, Dokku could not determine how to start the web process.

### Resolution:
Ensure the repository includes a valid Procfile or that the buildpack generates one automatically during the build phase.

This is the buildpack that is compatible:

```
https://github.com/heroku/heroku-buildpack-php#v190
```

## Issue 2: Rebuild Failure – “EPOCHREALTIME: unbound variable”

When rebuilding the app:

```
-----> Cleaning up...
-----> Building alertme-za-staging from herokuish
-----> Adding BUILD_ENV to build environment...
-----> Fetching custom buildpack
-----> PHP app detected
remote: /tmp/buildpacks/custom/bin/util/build_report.sh: line 145: EPOCHREALTIME: unbound variable
remote: 2025/10/13 05:51:57 exit status 1
```

### Root Cause:
The buildpack used a shell variable EPOCHREALTIME which is available only in Bash 5+. The environment in Dokku’s Herokuish builder may use an older or restricted shell environment.

### Resolution:
Update the custom buildpack to handle environments without EPOCHREALTIME, or define a fallback for it.

## Issue 3: Deployment from Local – “EPOCHREALTIME: unbound variable”

During local deployment (git push dokku master):

```
Enumerating objects: 33437, done.
Counting objects: 100% (33437/33437), done.
Delta compression using up to 10 threads
Compressing objects: 100% (10231/10231), done.
Writing objects: 100% (33437/33437), 10.73 MiB | 944.00 KiB/s, done.
remote: Resolving deltas: 100% (22537/22537), done.
remote: 2025/10/13 05:51:46 exit status 141
-----> Cleaning up...
-----> Building alertme-za-staging from herokuish
-----> Adding BUILD_ENV to build environment...
-----> Fetching custom buildpack
-----> PHP app detected
remote: /tmp/buildpacks/custom/bin/util/build_report.sh: line 145: EPOCHREALTIME: unbound variable
remote: 2025/10/13 05:51:57 exit status 1
To dokku-1.hurumap.org:alertme-za-staging
! [remote rejected]   master -> master (pre-receive hook declined)
error: failed to push some refs to 'dokku-1.hurumap.org:alertme-za-staging'
```

### Root Cause:
Same as Issue 2 — the build process failed due to the EPOCHREALTIME variable not being available in the build environment.

### Resolution:
Patch the buildpack script or update the base image to a version of Bash supporting EPOCHREALTIME.

## Issue 4: Database Configuration Error – sql_mode Compatibility

The main culprit that broke the Laravel application after upgrading MySQL was:

```
Syntax error or access violation: 1231 Variable 'sql_mode' can't be set to the value of 'NO_AUTO_CREATE_USER'

```
### Explanation:
The NO_AUTO_CREATE_USER mode was removed in MySQL 8.0, but older Laravel configurations still attempt to set it explicitly.


Fix in Configuration:

```
'strict' => false,
'modes' => [
    'NO_ENGINE_SUBSTITUTION',
],
```

Resolution:
Remove NO_AUTO_CREATE_USER from the SQL modes and disable strict mode if necessary for backward compatibility.

### 3. Summary of Fixes


| Issue                  | Root Cause                            | Fix Implemented                            |
|-------------------------|---------------------------------------|--------------------------------------------|
| **Restart failure**     | Missing `Procfile`                    | Added or auto-generated `Procfile`         |
| **Rebuild failure**     | `EPOCHREALTIME` undefined in buildpack| Added compatible Bash/buildpack specifically v190 |
| **Deployment failure**  | Same as rebuild issue                 | Same fix applied                           |
| **SQL mode incompatibility** | Deprecated `NO_AUTO_CREATE_USER` | Updated `database.php` configuration       |

### 4. Lessons Learned

Always validate Laravel MySQL configurations against the new MySQL version before upgrading.

Ensure Dokku/Herokuish buildpacks are compatible with the runtime environment.

Maintain Procfiles and CHECKS files for more predictable Dokku deployments.

Test upgrades in a staging environment before applying to production.