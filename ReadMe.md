# Installation
## TYPO3 download based instance
If you downloaded and unpacked a full TYPO3 core from typo3.org or get.typo3.org, just install the system as usual. In the last installation step you can select the option Yes, download the list of distributions. On first backend login, you will be redirected to the extension manager “Get preconfigured distribution” and can install the “GSB Package” with one click.

If you want to test the distribution in an existing instance, just select the extension manager in the backend, go to “Get preconfigured distribution” and select “The Official TYPO3 Introduction Package”.

Note the extension can be unloaded and removed from the system after initial import, all business code is within the bootstrap_package extension that comes along with the introduction extension, all required content data is loaded into the database and required files are put into fileadmin/introduction upon first installation.

## Composer based instance
If you base your TYPO3 instance on a modern composer based installation, just require the package via composer:

composer require itzbund/gsb-template
