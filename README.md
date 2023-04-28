<a name="readme-top"></a>



<!-- PROJECT SHIELDS -->
[![pipeline status](https://git.gsb-itzbund.de/gsb11/extensions/gsb_core/badges/main/pipeline.svg)][pipeline-url]
[![Latest Release](https://git.gsb-itzbund.de/gsb11/extensions/gsb_core/-/badges/release.svg)][release-url]



<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://git.gsb-itzbund.de/gsb11extensions/gsb_core">
    <img src="https://produkt.gsb.bund.de/SiteGlobals/Frontend/Images/logo.png?__blob=normal&v=1" alt="Logo" width="300">
  </a>

<h3 align="center">Government Side Builder (GSB) - Core</h3>

  <p align="center">
    Die Content Management Lösung für die deutsche Bundesverwaltung - von der Verwaltung für die Verwaltung
    <br />
    <br />
    <a href="https://demo.gsb-itzbund.de/">Demo ansehen</a>
    ·
    <a href="https://jira.powerofone.de/jira/secure/CreateIssue!default.jspa?pid=21636&issuetype=1">Fehler melden</a>
    ·
    <a href="https://jira.powerofone.de/jira/secure/CreateIssue.jspa?pid=21636&issuetype=10">Feature anfragen</a>
  </p>
</div>



<!-- TABLE OF CONTENTS -->
<details>
  <summary>Inhaltsverzeichnis</summary>
  <ol>
    <li><a href="#uber-das-projekt">Über das Projekt</a></li>
    <li><a href="#erste-schritte-mit-composer">Erste Schritte mit Composer</a></li>
    <li><a href="#erste-schritte-mit-ddev">Erste Schritte mit DDEV</a></li>
    <li><a href="#roadmap">Roadmap</a></li>
    <li><a href="#mitmachen">Mitmachen</a></li>
    <li><a href="#lizenz">Lizenz</a></li>
    <li><a href="#kontakt">Kontakt</a></li>
    <li><a href="#danksagungen">Danksagungen</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## Über das Projekt

Der Government Site Builder ist das nutzerzentrierte und zukunftssichere Content Management System der Bundesverwaltung.


### Erstellt mit

* [TYPO3][typo3-url]
* [Bootstrap][bootstrap-url]



<!-- GETTING STARTED -->
## Erste Schritte mit Composer

Nutze den GSB in deinem nächsten Projekt. Schnell und einfach mit Composer.


### Voraussetzungen

- Informationen zu den [allgemeinen TYPO3 Systemvoraussetzungen][typo3-requirements-url]
- Installiere [Composer][composer-url]


### Installation

1. Erstelle ein neues Projekt
   ```sh
   composer create-project "typo3/cms-base-distribution:^12" my-gsb-project
   ```
1. Wechsle in das neue Projektverzeichnis
   ```sh
   cd my-gsb-project
   ```
1. Mache Composer mit dem GSB Git bekannt
   ```sh
   composer config gitlab-domains git.gsb-itzbund.de
   ```
1. Verbinde dich mit der Paketregistrierung des GSB
   ```sh
   composer config repositories.6 composer https://git.gsb-itzbund.de/api/v4/group/6/-/packages/composer/
   ```
1. Erstelle eine auth.json-Datei mit deinen GitLab-Anmeldedaten:
   ```sh
   composer config gitlab-token.git.gsb-itzbund.de <personal_access_token>
   ```
   Alternativ kannst du auch einen GitLab-Deploy-Token verwenden:
   ```sh
    composer config gitlab-token.git.gsb-itzbund.de <deploy_token_username> <deploy_token>
    ```
1. Temporärer Schritt: setze minimum-stability auf dev
   ```sh
   composer config minimum-stability dev
   ```
1. Füge den GSB zu deinem Projekt hinzu
   ```sh
   composer require itzbund/gsb-core
   ```

1. (Temporär) Füge dem GSB-Template den Public Ordner hinzu. Public-Ordner kann über die Releases (GSB Distribution.zip) bezogen werden.
   ```sh
   cp Public /opt/typo3/vendor/itzbund/gsb-core/Resources/Public
   ```

1. TYPO3 installieren. Ggf frisch anlegen bzw. dropen. Ab "V 12.2 mit vendor/bin/typo3 ..." arbeiten
   ```sh
   vendor/bin/typo3 setup --dbname=*DBNAME* --admin-username=*ADMIN* --admin-user-password=*PASSWORD*
   ```
1. .htaccess im Public-Ordner anlegen
Nutze die Datei https://github.com/TYPO3/typo3/blob/main/typo3/sysext/install/Resources/Private/FolderStructureTemplateFiles/root-htaccess als Basis für die .htaccess Datei
1. Rechte setzen. Je nach Linux-Distribution bitte entsprechend anpassen.
    ```sh
   chown x:x -R config/ var/ public/typo3temp/ public/fileadmin/
   ```
1. Installiere die Extensions. (Anmerkung V 12.2 über vendor gehen)
   ```sh
   vendor/bin/typo3 extension:setup
   ```
1. (Temporär) _assets-Ordner unter public löschen und einmal composer install ausführen
   ```sh
   rm -R public/_assets && cp distribution-package-gsb_template/Resources/Public/ /opt/typo3/vendor/itzbund/gsb-core/Resources/ -R
   ```


<!-- GETTING STARTED -->
## Erste Schritte mit DDEV

Mit DDEV kannst du dir schnell eine lokale, in Dockercontainern gekapselte Entwicklungsumgebung aufsetzen.

Wir haben eine vorkonfigurierte DDEV-Umgebung für dich vorbereitet. Auf https://git.gsb-itzbund.de/gsb11/local-dev findest du alle Informationen.



<!-- ROADMAP -->
## Roadmap

Eine vollständige Liste der vorgeschlagenen neuen Funktionen (und bekannten Probleme) findest du in unserem [Jira][jira-backlog-url].



<!-- CONTRIBUTING -->
## Mitmachen

Beiträge sind es, die die Open-Source-Gemeinschaft zu einem so wunderbaren Ort des Lernens, der Inspiration und der Kreativität machen. Jeder Beitrag ist uns sehr willkommen. Ganz nach der TYPO3 Vision **Inspiring people to share**.

Du kannst jederzeit
- [Fehler melden][jira-bug-url]
- [Feature anfragen][jira-story-url]

Wenn du mitentwickelst, halte dich an unsere Standards
- Branching
  - [Git Feature Branch Workflow][git-workflow-url]
  - Benennung der Branches
     ```sh
     feature/ITZBUNDPHP-123-kurze-beschreibung
     ````
     Ticket Id ist optional
- Commits
  - [Conventional Commits][conventionalcommits-url]
  - Commitsprache ist English

- Coding Standards
  - [TYPO3 Coding Guidelines][typo3-coding-guidelines-url]
  - [PSR-12][psr12-url]
  - Das GSB Distribution Paket enthält alle notwendigen Konfigurationen um die Coding Standards zu prüfen.
  - In diesem Paket wird jedes Feature möglichst TYPO3 Core nah entwickelt.

### Release Workflow
Zum Erstellen eines neuen Releases folgt der Release Workflow folgende Schritte:
- Erstellen eines neuen Merge Requests in GitLab mit dem Zielbranch `release` und dem Quellbranch `main`
- Auswählen des Templates "release"
- Ergänzen der Release Informationen
- Merge Request mergen

Vor dem start der Entwicklung an einer neuen Versione sind folgende Schritte durchzuführen:
- Erstellen eines neuen Merge Requests in GitLab mit dem Zielbranch `main` und dem Quellbranch `release`
- Mergen des Merge Requests


<!-- LICENSE -->
## Lizenz

Der Government Side Builder wird unter der GNU General Public License, Version 2 vertrieben. Siehe `LICENSE` für mehr Informationen zur Lizenz.

Und [TYPO3's Open Source Licenses][typo3-licenses-url] für einen generellen Überblick zu den Lizenzen im TYPO3 Projekt.



<!-- CONTACT -->
## Kontakt

gsb@itzbund.de



<!-- ACKNOWLEDGMENTS -->
## Danksagungen

* [Best README Template](https://github.com/othneildrew/Best-README-Template)

<p align="right">(<a href="#readme-top">back to top</a>)</p>



<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[bootstrap-url]: https://getbootstrap.com
[composer-url]: https://getcomposer.org/
[conventionalcommits-url]: https://www.conventionalcommits.org/en/v1.0.0/
[git-workflow-url]: https://www.atlassian.com/git/tutorials/comparing-workflows/feature-branch-workflow
[jira-backlog-url]: https://jira.powerofone.de/jira/secure/RapidBoard.jspa?rapidView=2924&projectKey=ITZBUNDPHP&view=planning&issueLimit=100
[jira-bug-url]: https://jira.powerofone.de/jira/secure/CreateIssue!default.jspa?pid=21636&issuetype=1
[jira-story-url]: https://jira.powerofone.de/jira/secure/CreateIssue.jspa?pid=21636&issuetype=10
[pipeline-url]: https://git.gsb-itzbund.de/gsb11/distribution-package-gsb_template/-/commits/main
[release-url]: https://git.gsb-itzbund.de/gsb11/distribution-package-gsb_template/-/releases
[typo3-url]: https://get.typo3.org/
[typo3-licenses-url]: https://typo3.org/project/licenses
[typo3-requirements-url]: https://get.typo3.org/version/12#system-requirements
[typo3-coding-guidelines-url]: https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/CodingGuidelines/Index.html
[psr12-url]: https://www.php-fig.org/psr/psr-12/

