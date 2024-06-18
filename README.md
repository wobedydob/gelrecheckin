# 🛫 GelreCheckin

## 🧾 Casus
Op Gelre Airport is er een systeem nodig: Gelre Checkin om de passagiers en de medewerkers te ondersteunen op het vliegveld zelf.

Passagiers willen graag hun koffers inchecken (aangeven hoeveel gewicht ze meenemen aan baggage) en informatie over hun eigen vlucht inzien.

Medewerkers willen graag passagiers helpen met een nieuwe vlucht boeken, omboeken en koffers inchecken.

## 🖼️ Framework
Voor dit project heb ik zelf een framework ontwikkeld. Dit framework is gebaseerd op Laravel, en wordt gebruikt om de applicatie te bouwen.
Ook zit er een mini front-end framework in dat gebaseerd is op Tailwind.

Met dit framework kan gemakkelijk Database models gemaakt worden, er kunnen ook gemakkelijk CRUD functionaliteiten op uitgevoerd worden.
De Models worden altijd in hun eigen class gemapt, en/of als een Collection terug gegeven (van zijn eigen Model).

### 🪂 Features
- **Query Builder:** De Query class wordt gebruikt om queries uit te voeren. Dit wordt gebruikt door Models om queries uit te voeren.
- **Models:** Deze objecten worden gebruikt om Database queries uit te voeren op hun desbetreffende tabel.
- **Routing:** De routing is gebaseerd op Laravel. Met routing kunnen gebruikers naar andere locaties navigeren en krijgen pagina's geregistreerd onder zijn eigen URL.
- **Authentication:** De authenticatie van gebruikers is gebaseerd op Laravel. Er wordt gebruikt om de gebruiker in te loggen, te kunnen kijken of de gebruiker is ingelogd en te kunnen uitloggen.
- **Controllers:** De controllers zijn gebaseerd op Laravel. Er wordt gebruikt om de routing en de authenticatie van gebruikers te laten werken.
- **Views:** De views zijn gebaseerd op Laravel. Er wordt gebruikt om de frontend van de applicatie te laten zien.

## 👨‍💻 Development
Deze applicatie is in een Docker omgeving gebouwd.

### 📋 Benodigdheden
- **Docker**: Docker is een open-source platform om container-based applicaties te bouwen.

### ⚙️ Installation
1. Clone de repository.
2. Open het project in de terminal.
3. Build de applicatie met `docker compose up --build`.
