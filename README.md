# Project requirements and details

<details>
<summary>🇫🇷 Français</summary>

Votre projet consistera à developper seul.e ou en binôme un site permettant de voter et d’organiser des scrutins en ligne. Vous pourrez regarder des sites existants en lignes comme [Belenios](https://www.belenios.org/) ou [Balotilo](https://www.balotilo.org/). Le site que vous développerez devra respecter un cahier des charges particulier :

* Les votants et l’organisateur ne seront pas anonymes (login-mot de passe requis)
* Les votes sont anonymes (A la fin on ne sait pas qui a voté quoi)
* La liste des personnes ayant votés est semi anonyme. Le système doit garder une liste de personnes ayant voté pour éviter de les laisser voter plus de fois que nécessaire mais la liste n’est pas affichée.
* Les votants pourront porter 0, 1 ou 2 procurations. La personne donnant procurationd pouvant donner des consignes de vote il conviendra de faire voter 1,2 ou trois fois le votant indépendamment. C’est l’organisateur du vote qui renseigne qui a donné procuration à qui.
* L’organisateur du vote souhaite gérer des listes de votants afin d’enchainer les scrutins (ie, une liste L3 miage, une liste L3 info, une liste Enseignant-Prog-Web, etc.). C’est dans ces listes de votants qu’il renseigne combien de procuration porte chaque votant (0, 1 ou 2)

Un scrutin sera toujours composé au moins des trois éléments suivant que l’organisateur du scrutin renseignera quand il se sera identifié:

* Une question
* Des options (= des choix de réponses à la question)
* Une liste de votants (chacun avec un nombre 0,1 ou 2 procurations)
* Une liste de votes (liste de bulletins)
* Une liste de qui a voté et combien de fois.

Un votant pourra consulter la liste des scrutins dans lesquels il peut voter, voter bien sûr, mais aussi consulter le taux de participation et le nom de l’organisateur. L’organisateur pourra aussi consulter le taux de participation et voter aux scrutins dans lesquels il s’inscrit lui-même comme votant. Quand l’organisateur le décide il peut clore le scrutin. L’organisateur et tous les votants peuvent alors consulter le résultat (nombre absolue et pourcentage obtenus pour chaque option). Le cas le plus classique est une reunion on 30 personnes doivent voter. 10 sont absents et parmi ces 10, cinq ont donné une procuration explicite à quelqu’un. Il faut donc pouvoir créer une liste de 20 noms dont 5 peuvent voter 2 fois. Au cours de la reunion par Visio plusieurs point sont soumis au vote. A chaque fois l’organisateur re-utilise la même liste, change l’intitulé de la question et crée un nouveau scrutin. Il laisse quelques secondes pour voter, regarde la participation, relance les votants et quand il lui semble que tout le monde a voté il ferme le scrutin et annonce le résultat. Au cours de la reunion un participant s’en va et laisse procuration à quelqu’un d’autre. Au vote suivant l’organisateur le retire de la liste et rajoute un +1vote à celui qui porte la procuration.

Le résultat attendu pour un binôme est forcement plus grand. Nous vous proposant plusieurs extension dont les binômes devront avoir au moins couvert la moitié.

* Liste par vote préférentiel. Dans ce cas les votants ne choisissent pas une seule option mais doivent classer les options. En fait ils votent une fois pour le premier choix, une fois pour leur deuxième choix , etc. Ils peuvent mettre la même option a plusieurs niveaux ou ne pas choisir de deuxième choix. Dans tous les cas le dépouillement est plus compliqué. On regarde les premiers choix et on trouve le moins choisi. On enlève cette option et on remplace les premiers choix pour cette options par les second choix. On recommence jusqu’à qu’il ne reste plus qu’une option (le gagnant) ou plusieurs options ex-aequo (et on comptabilise les choix 2 pour les options restantes afin de les départager).

* Date. Vous pourrez permettre de choisir les options dans un calendrier (dates, plages horaires). Comme le système doodle. Pour l’organisateur comme pour le votant, les plages horaires seront affichées dans un calendrier.

* Encryption des votes. A l’aide de la librairie JSEncrypt vous pourrez encrypter les scrutins. Un clé privé sera générée par l’organisateur qui la gardera localement (localStorage). La clé public associée sera publiée avec le scrutin et chaque votant l’utilisera pour envoyer une version cryptée de son vote au serveur. Lors du dépouillement les votes cryptés (et mélangés) sont tous récupérés localement sur le navigateur de l’organisateur qui décode les votes avec sa clé privée.

* Interface de procuration. Plutôt que de renseigner le nombre de procurations pour chaque votant, l’organisateur du scrutin peut simplement fixer une liste complète à l’avance et les votants peuvent venir à l’avance indiquer qui peut porter leur procuration. Le système prendra la première personne présente dans la liste pour porter la procuration.

Si vous voyez d’autres extensions interessantes, vous la soumettrez à votre enseignant avant de la programmer. Tous les programmes devront être commentés et testés. L’interface que vous développerez sera principalement mono-page (une page pour l’organisateur et une autre page pour le votant), il n’y aura donc pas de rechargement de la page pendant les actions.

</details>


<details>
    <summary>🇮🇹 Italiano</summary>

Il vostro progetto consisterà nello sviluppare da soli o in coppia un sito web per votare e organizzare elezioni online. Potete consultare siti esistenti come [Belenios](https://www.belenios.org/) o [Balotilo](https://www.balotilo.org/). Il sito che svilupperete dovrà rispettare un particolare elenco di requisiti:

* Gli elettori e l'organizzatore non saranno anonimi (richiesta di login e password)
* I voti sono anonimi (alla fine non si sa chi ha votato cosa)
* L'elenco delle persone che hanno votato è semi-anonimo. Il sistema deve mantenere un elenco delle persone che hanno votato per evitare che votino più volte del necessario, ma l'elenco non è visualizzato.
* Gli elettori possono avere 0, 1 o 2 deleghe. La persona che concede la delega può dare istruzioni di voto, quindi è necessario far votare il delegante 1, 2 o 3 volte indipendentemente. È l'organizzatore del voto che specifica chi ha dato la delega a chi.
* L'organizzatore del voto desidera gestire elenchi di elettori per concatenare le elezioni (ad esempio, una lista L3 informatica, una lista L3 informatica, una lista Insegnante-Programmatore-Web, ecc.). È in questi elenchi di elettori che specifica quante deleghe ha ciascun elettore (0, 1 o 2).

Un'elezione sarà sempre composta almeno dai tre seguenti elementi, che l'organizzatore dell'elezione inserirà quando si sarà identificato:

* Una domanda
* Opzioni (= scelte di risposta alla domanda)
* Un elenco di elettori (ciascuno con un numero di 0, 1 o 2 deleghe)
* Un elenco di voti (elenco delle schede)
* Un elenco di chi ha votato e quante volte.

Un elettore potrà consultare l'elenco delle elezioni alle quali può votare, votare ovviamente, ma anche consultare il tasso di partecipazione e il nome dell'organizzatore. L'organizzatore potrà anche consultare il tasso di partecipazione e votare alle elezioni alle quali si iscrive come elettore. Quando l'organizzatore lo decide, può chiudere l'elezione. L'organizzatore e tutti gli elettori possono quindi consultare i risultati (numero assoluto e percentuale ottenuta per ogni opzione). Il caso più comune è una riunione in cui 30 persone devono votare. Dieci sono assenti e tra questi dieci, cinque hanno dato una delega a qualcuno. Quindi è necessario poter creare un elenco di 20 nomi di cui 5 possono votare 2 volte. Durante la riunione tramite videoconferenza, vengono presentati diversi punti per il voto. Ogni volta l'organizzatore riutilizza lo stesso elenco, cambia il titolo della domanda e crea una nuova elezione. Lascia qualche secondo per votare, controlla la partecipazione, incoraggia gli elettori e quando sembra che tutti abbiano votato, chiude l'elezione e annuncia i risultati. Durante la riunione, un partecipante si allontana e affida la sua delega a qualcun altro. Al voto successivo, l'organizzatore lo rimuove dall'elenco e aggiunge un +1 voto a chi ha ricevuto la delega.

Il risultato atteso per una coppia è naturalmente più ampio. Vi proponiamo diverse estensioni, e i binomi dovranno averne coperta almeno la metà.

* Lista per voto preferenziale. In questo caso, gli elettori non scelgono una sola opzione, ma devono classificare le opzioni. Effettivamente, votano una volta per la prima scelta, una volta per la seconda scelta, ecc. Possono mettere la stessa opzione a più livelli o non scegliere una seconda opzione. In tutti i casi, lo scrutinio è più complicato. Si guardano le prime scelte e si trova quella meno scelta. Si rimuove questa opzione e si sostituiscono le prime scelte per questa opzione con le seconde scelte. Si ripete finché rimane solo un'opzione (il vincitore) o più opzioni ex aequo (e si contano le scelte 2 per le opzioni rimanenti per differenziarle).

* Data. Potrete permettere di scegliere le opzioni in un calendario (date, fasce orarie). Come il sistema doodle. Per l'organizzatore e per l'elettore, le fasce orarie saranno visualizzate in un calendario.

* Crittografia dei voti. Utilizzando la libreria JSEncrypt potrete crittografare gli scrutini. Una chiave privata sarà generata dall'organizzatore che la manterrà localmente (localStorage). La chiave pubblica associata sarà pubblicata con lo scrutinio e ogni elettore la utilizzerà per inviare una versione crittografata del suo voto al server. Durante lo scrutinio, i voti criptati (e mescolati) vengono tutti recuperati localmente sul browser dell'organizzatore che decodifica i voti con la sua chiave privata.

* Interfaccia per la delega. Piuttosto che specificare il numero di deleghe per ogni elettore, l'organizzatore dell'elezione può fissare in anticipo un elenco completo e gli elettori possono indicare in anticipo chi può portare la loro delega. Il sistema sceglierà la prima persona presente nell'elenco per portare la delega.

Se vedete altre estensioni interessanti, le sottoporrerete al vostro insegnante prima di programmarle. Tutti i programmi dovranno essere commentati e testati. L'interfaccia che svilupperete sarà principalmente monopagina (una pagina per l'organizzatore e una per l'elettore), quindi non ci saranno ricariche di pagina durante le azioni.

</details>

<details open>
<summary>🇺🇸 English</summary>

Your project will involve developing a website on your own or in pairs for voting and organizing online polls. You can explore existing sites like [Belenios](https://www.belenios.org/) or [Balotilo](https://www.balotilo.org/). The site you develop must adhere to a specific set of requirements:

* Voters and the organizer will not be anonymous (login-password required).
* Votes are anonymous (at the end, it's unknown who voted for what).
* The list of people who voted is semi-anonymous. The system must maintain a list of people who voted to prevent them from voting more times than necessary, but the list is not displayed.
* Voters can have 0, 1, or 2 proxies. The person granting a proxy can give voting instructions, so it's necessary to make the voter vote 1, 2, or 3 times independently. The vote organizer specifies who gave a proxy to whom.
* The vote organizer wants to manage lists of voters to link elections (e.g., a list L3 Computer Science, a list L3 Information, a list Teacher-Programmer-Web, etc.). It is in these lists of voters that they specify how many proxies each voter carries (0, 1, or 2).

A ballot will always consist of at least the following three elements, which the vote organizer will fill in when identified:

* A question
* Options (= choices of answers to the question)
* A list of voters (each with a number of 0, 1, or 2 proxies)
* A list of votes (list of ballots)
* A list of who voted and how many times.

A voter can view the list of polls they can vote in, vote, and also check the participation rate and the organizer's name. The organizer can also check the participation rate and vote in the polls in which they register as voters. When the organizer decides, they can close the poll. The organizer and all voters can then check the results (absolute number and percentage obtained for each option). The most common scenario is a meeting where 30 people need to vote. Ten are absent, and among these ten, five have given a proxy to someone. So it's necessary to create a list of 20 names, of which 5 can vote 2 times. During the video conference meeting, several points are submitted for voting. Each time, the organizer reuses the same list, changes the question title, and creates a new poll. They leave a few seconds to vote, check participation, encourage voters, and when it seems everyone has voted, close the poll and announce the results. During the meeting, a participant leaves and delegates their vote to someone else. In the next vote, the organizer removes them from the list and adds +1 vote to the person who received the proxy.

The expected result for a pair is naturally more extensive. We offer several extensions, and pairs must have covered at least half.

* Ranked-choice voting. In this case, voters don't choose a single option but must rank the options. They vote once for the first choice, once for their second choice, etc. They can put the same option at multiple levels or not choose a second option. In all cases, counting is more complicated. First choices are examined, and the least chosen is identified. That option is removed, and first choices for that option are replaced with second choices. This process repeats until only one option remains (the winner) or multiple options are tied (and second-choice votes are counted to break the tie).

* Date. You can allow choosing options on a calendar (dates, time slots). Like the doodle system. For both the organizer and the voter, time slots will be displayed on a calendar.

* Vote encryption. Using the JSEncrypt library, you can encrypt the ballots. A private key will be generated by the organizer and stored locally (localStorage). The associated public key will be published with the poll, and each voter will use it to send an encrypted version of their vote to the server. During counting, the encrypted (and shuffled) votes are retrieved locally on the organizer's browser, who decodes the votes with their private key.

* Proxy interface. Rather than specifying the number of proxies for each voter, the election organizer can pre-set a complete list, and voters can indicate in advance who can carry their proxy. The system will choose the first person on the list to carry the proxy.

If you see other interesting extensions, you should submit them to your teacher before implementing them. All programs must be commented and tested. The interface you develop will be mainly single-page (one page for the organizer and one for the voter), so there will be no page reloads during actions.

</details>