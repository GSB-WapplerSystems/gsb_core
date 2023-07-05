# Singleteaser

This is the technical documentation for the Singleteaser. The next steps describe where the Singleteaser Teaser is located and where you can edit it.

At the following location we find the fluid file for the **singleteaser**.

```
Resources/Private/Templates/Content/Singleteaser.html
```

There are a few points to note about the singleteaser. Partials are used here which in turn are reused for other content elements. This means that if we edit the files in the following lines they will have an effect wherever these partials are used.

- The following path contains the configurations for all headings for the page. If you adjust these, it will **have an effect on all headings**.
```
Resources/Extensions/fluid_styled_content/Private/Partials/Header/Header.html
```

- The implementation for the body text takes place in this partial. It should also be noted here that **changes to the bodytext.html affect the entire page**.

```
Resources/Private/Partials/Content/Media/Bodytext.html
```

- Editing the Button.html also affects **all buttons on the page**. If you want to expand them it is intended that you do so at this point.

```
Resources/Private/Partials/Content/Media/Button.html
```

All these files are in rellation to the **Button.html**.

**To learn more about Fluid and TypoScript we refer to the TYPO3 documentation. It is important to use the correct documentation for the given TYPO3 version.**
