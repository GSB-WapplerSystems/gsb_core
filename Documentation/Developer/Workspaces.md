# Workspaces

## Introduction

In this Package we use the TYPO3 Workspaces to manage the content of the website.

## Workspaces

The following workspaces are used in this project:
- Einfacher Freigabeworkflow
- Live

The Default Workspace Configuration get importet on every deployment. The Configuration is located in the file `Configuration/Workspaces/BaseWorflow.yaml`.

For more details check the Class `ITZBund\GsbCore\Command\ConfigurationImportCommand`.
he Import Command will Import from all files in the folder `Configuration/Workspaces/`, in any Extension!
