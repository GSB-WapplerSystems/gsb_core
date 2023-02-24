 ##Favicons
 *Uses*: https://realfavicongenerator.net/
 ### You can automate the favicon creation using a Node.js command-line interface:

Install cli-real-favicon:

    npm install -g cli-real-favicon

Create a file named faviconDescription.json and populate it with:

    {
    	"masterPicture": "TODO: Path to your master picture",
    	"iconsPath": "typo3conf/ext/your_template/Resources/Favicons",
    	"design": {
    		"ios": {
    			"pictureAspect": "backgroundAndMargin",
    			"backgroundColor": "#333333",
    			"margin": "18%",
    			"assets": {
    				"ios6AndPriorIcons": false,
    				"ios7AndLaterIcons": false,
    				"precomposedIcons": false,
    				"declareOnlyDefaultIcon": true
    			},
    			"appName": "GSBStandard"
    		},
    		"desktopBrowser": {},
    		"windows": {
    			"pictureAspect": "noChange",
    			"backgroundColor": "#333333",
    			"onConflict": "override",
    			"assets": {
    				"windows80Ie10Tile": false,
    				"windows10Ie11EdgeTiles": {
    					"small": true,
    					"medium": true,
    					"big": true,
    					"rectangle": true
    				}
    			},
    			"appName": "GSBStandard"
    		},
    		"androidChrome": {
    			"pictureAspect": "shadow",
    			"themeColor": "#333333",
    			"manifest": {
    				"name": "yourname",
    				"startUrl": "https://www.yourdomain.de",
    				"display": "standalone",
    				"orientation": "notSet",
    				"onConflict": "override",
    				"declared": true
    			},
    			"assets": {
    				"legacyIcon": false,
    				"lowResolutionIcons": false
    			}
    		},
    		"safariPinnedTab": {
    			"pictureAspect": "blackAndWhite",
    			"threshold": 50,
    			"themeColor": "#333333"
    		}
    	},
    	"settings": {
    		"compression": 5,
    		"scalingAlgorithm": "Mitchell",
    		"errorOnImageTooSmall": false,
    		"readmeFile": true,
    		"htmlCodeFile": true,
    		"usePathAsIs": false
    	}
    }

In the code above, replace TODO: Path to your master picture with the path of your master picture. For example, assets/images/favicon.png. Generate your icons:

    real-favicon generate faviconDescription.json faviconData.json Assets/Static/Favicons

Generate them to "Assets/Static/Favicons", run npm build to copy the icons to the public folder

Inject the HTML code in your pages:

    real-favicon inject faviconData.json outputDir htmlFiles/*.html

Check for updates (to be run from time to time, ideally by your continuous integration system):

    real-favicon check-for-update --fail-on-update faviconData.json

Upload "Resources/Favicons" to your server or deploy

###Get the HTML code from generated

    typo3conf/ext/your_template/Resources/Favicons/html_code.html

and put it in

    typo3conf/ext/gsb_template/Resources/Private/Partials/Page/Favicons.html

Deploy the Extension. Done :)

*Optional* - Check your favicon with the [favicon checker](https://realfavicongenerator.net/favicon_checker)
