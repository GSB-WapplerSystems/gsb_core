<!--
SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund

SPDX-License-Identifier: GPL-3.0-or-later
-->

# Backend User Groups and Permissions

## Introduction

We have an Import Command to create the Backend User Groups and use b13/permissions to manage the permissions.

## Backend User Groups

The Backend User Groups are created in the file `Configuration/BackendUserGroups/Base.yaml`.

For more details check the Class `ITZBund\GsbCore\Command\ConfigurationImportCommand`.
The Import Command will Import from all files in the folder `Configuration/BackendUserGroups/`, in any Extension!


## Permissions

The Permissions are managed in the file `Configuration/PermissionSets/*.yaml`. Pls check the documentation of b13/permissions for more details.

Some more Permissions are Configured via TSConfig in the file `Configuration/TSConfig/*`. In the file `Configuration/TSConfig/User/*` you can find the TSConfig for the Backend User Groups.
