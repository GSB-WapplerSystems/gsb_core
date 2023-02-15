<a name="readme-top"></a>



<!-- PROJECT SHIELDS -->
[![pipeline status](https://git.gsb-itzbund.de/gsb11/distribution-package-gsb_template/badges/main/pipeline.svg)][pipeline-url] 
[![Latest Release](https://git.gsb-itzbund.de/gsb11/distribution-package-gsb_template/-/badges/release.svg)][release-url]



<!-- PROJECT LOGO -->
<br />
<div align="center">
  <a href="https://git.gsb-itzbund.de/gsb11/distribution-package-gsb_template">
    <img src="https://produkt.gsb.bund.de/SiteGlobals/Frontend/Images/logo.png?__blob=normal&v=1" alt="Logo" width="300">
  </a>

<h3 align="center">Government Side Builder (GSB) - Distribution</h3>

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
## Über das Projekt (Entwurf)

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
1. Temporärer Schritt: setze minimum-stability auf dev
   ```sh
   composer config minimum-stability dev
   ```
1. Füge den GSB zu deinem Projekt hinzu
   ```sh
   composer require itzbund/gsb-template
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



<!-- LICENSE -->
## Lizenz

Der Government Side Builder wird unter der GNU General Public License, Version 2 vertrieben. Siehe `LICENSE.txt` für mehr Informationen zur Lizenz. 

Und [TYPO3's Open Source Licenses][typo3-licenses-url] für einen generellen Überblick zu den Lizenzen im TYPO3 Projekt.



<!-- CONTACT -->
## Kontakt

Your Name - email@email_client.com



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