# Rechte und Rollen

Die Distrubtion liefert bei der ersten Installation folgende Gruppen für TYPO3-Backend aus:
- [ROLE] Site Administrator:innen
- [ROLE] Chefredakteur:innen
- [ROLE] Redakteur:innen
- [PAGEACCESS] Base
- [FM] Base
- [DBM] Base
- [DBM] Spielwiese

FM = File Mount, DBM = Database Mount

## Rollen

### Site Administrator:innen

Diese Gruppe hat Zugriff auf alle Seiten und alle Module. Sie können auch neue Seiten anlegen und löschen.

### Chefredakteur:innen

Diese Gruppe hat Zugriff auf alle Seiten, aber nicht auf alle Module. Sie können auch neue Seiten anlegen und löschen.
Alle Änderungen an Seiten können freigegeben werden.

### Redakteur:innen

Diese Gruppe hat Zugriff auf die zugewiesen Seiten, aber nicht auf alle Module. Sie können neuen Seiten anlegen und löschen.
Alle Änderungen an Seiten werden von einem Chefredakteuer:innen oder Site Administrator:in freigegeben.
Es besteht Zugriff auf alle Content-Elemente.

## Zugriffskontrolle (ACL)

Für die Zugriffskontrolle (ACL) wird das Extension b13/permission-sets eingesetz.
Die Distribution liefet folgende ACL-Dateien aus:
- acl-editor.yaml
- acl-editor-in-chief.yaml
- acl-site-admin.yaml

Die Permissions Sets werden in den gleichnamigen Gruppen eingebunden und können durch Updates verändert werden.

Die Permission-Sets / ACL erben die Berechtigungen der jeweils niedrigeren Gruppe. Die Berechtigungen können in den Gruppen erweitert werden.

## Backendbenutzer

### Rechtezuweiseung
Den Backendbenutzer:innen ist immer eine ROLE-Gruppe so wie passende DBM-Gruppen zuzuweisen.

Die ROLE-Gruppe enthält mind. ein Permission Set, mind. eine FM-Gruppe und die PAGEACCESS-Gruppe.

### Standardbenutzer der Distribution
Die in der Distribution ausgelieferten Benutzer dienen als Beispiel und sollten nicht für die eigene Seite verwendet werden.

Die Benutzer sind:
- editor
- editor-in-chief
- site-admin
- editor-spielwiese

Das Passwort entspricht dem Benutzernamen, diese sind unbedingt zu ändern!


