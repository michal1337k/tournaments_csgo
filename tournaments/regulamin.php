<html>
<head>
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
require "../db/dbconn.php";
require '../steamauth/steamauth.php';

if(!isset($_SESSION['steamid'])) {

    header("Location: ../index.php");
}
else{
    include ('../steamauth/userInfo.php'); //To access the $steamprofile array
    $steamid = $steamprofile['steamid'];
    include ('../parts/menu.php');
    if (isset($_GET['id'])) {
        $id  = $_GET['id'];
        echo "<div class='tournamentsarea'>";  
        include('../parts/tournament_menu.php');
        echo "<h3>regulamin turnieju 5v5 #$id</h3>";
        echo "<div class='regulamin'>
        <h4>1. Postanowienia ogólne</h4>
        <p>1.1 Przystąpienie do turnieju jest równoznaczne z akceptacją niniejszego regulaminu.</p>
        <p>1.2 Poniższy Regulamin Turnieju (w skrócie „Regulamin”) dotyczy rozgrywek turnieju Counter-Strike: Global Offensive (w skrócie CS:GO).</p>
        <p>1.3 Organizatorzy zastrzegają sobie prawo do zmian w regulaminie na każdym etapie turnieju.</p>
        <p>1.4 Uczestnicy przystępując do turnieju zgadzają się na publikację meczu/próbek poprzez stream i inne media społecznościowe.</p>
        <p>1.5 Gracz biorący udział w turnieju nie może być zbanowany.</p>
        <p>1.6 Udział w turnieju jest otwarty dla graczy powyżej 16 roku życia.</p>
        <p>1.7 Osoby posiadające VAC Bana z gry CS:GO nie mogą uczestniczyć w turnieju.</p>
        <p>1.8 Liczba drużyn biorących udział w turnieju jest ograniczona do 8. W wyjątkowych przypadkach liczba drużyn może zostać zmieniona.</p>
        <p>1.9 Każdy z graczy biorących udział w turnieju ma obowiązek włączyć program anticheat dostępny do pobrania na stronie - <a href='https://ac.1shot1kill.pl/assets/download_solid/1shot1kill-anti-cheat-setup.exe'>LINK DO POBRANIA</a></p>
        <h4>2. Rejestracja graczy</h4>
        <p>2.1 Rejestracja graczy odbywa się poprzez kliknięcie przycisku dołącz na podstronie dołączenia do turnieju.</p>
        <p>2.2 Nicki graczy nie mogą być obraźliwe, niestosowne, ani nacechowane negatywnie (drużyny nie stosujące się do tego punktu będą banowane).</p>
        <p>2.3 O ważności rejestracji gracza decyduje administrator poprzez zaakceptowanie zgłoszenia.</p>
        <h4>3. Zasady fair play</h4>
        <p>3.1 Gracze są zobowiązani do traktowania swoich przeciwników z szacunkiem i poszanowaniem ludzkiej godności. Konsekwencje złego zachowania mogą zostać wyciągnięte przez Administratora.</p>
        <p>3.2 Niesportowe zachowanie w postaci wulgaryzmów czy prowokowania, zachowanie niezgodne z ogólnie rozumianymi normami społecznymi skierowane przeciwko innym uczestnikom wydarzenia, negatywne wypowiedzi dotyczące organizatora oraz współorganizatorów mogą skutkować nawet wykluczeniem z turnieju lub zbanowaniem przez Administratora.</p>
        <p>3.3 Zakazane są wszelkie czynności mogące naruszyć prawa autorskie oraz majątkowe wydawcy gry.</p>
        <p>3.4 W przypadku uzasadnionego podejrzenia oszukiwanie podczas gry, gracz może zostać usunięty z turnieju.</p>
        <p>3.5 W turnieju zabrania się:</p>
        <ul>
            <li>Grania na nielegalnych lub cudzych kontach STEAM,</li>
            <li>Używania wszelkich programów zapewniających jakąkolwiek przewagę podczas gry,</li>
            <li>Stosowania jakichkolwiek programów czy modyfikacji zmieniających oryginalną wersję gry,</li>
            <li>Celowego poddawania meczu przeciwnikowi.</li>
        </ul>
        <h4>4. Struktura turnieju</h4>
        <p>4.1 Obowiązuje system rozgrywek ESL 5vs5.</p>
        <p>4.2 W turnieju gramy do 1 wygranej mapy BO1 - MR15 - pierwsza drużyna, która zdobędzie 16 rund z przewagą conajmniej dwóch wygrywa.</p>
        <p>4.3 W przypadku remisu rozgrywana jest dogrywka MR 10k.</p>
        <p>4.4 Ustawienia zgodnie z wgranym configiem ESL 5vs5 na serwerze.</p>
        <p>4.5 Wszystkie mecze rozgrywane są w ciągu jednego dnia.</p>
        <p>4.6 Drużyna, która spóźni się więcej niż 15 minut na ustalony termin meczu odpada z turnieju i przeciwnik przechodzi dalej.</p>
        <p>4.7 Turniej rozgrywany jest na mapach: de_ancient, de_dust2, de_inferno, de_mirage, de_nuke, de_vertigo, de_overpass</p>
        <p>4.8 Gracze rozstawiani są w drabince zgodnie z systemem losującym zastosowanym przez organizatorów.</p>
        <p>4.9 Drabinka z meczami zostanie wygenerowana i udostępniona o godzinie rozpoczęcia turnieju.</p>
        <h4>5. Rozgrywka</h4>
        <p>5.1 Terminy meczów nie mogą być zmieniane, w wyjątkowych sytuacjach prosimy o kontakt z Administracją.</p>
        <p>5.2 W razie jakichkolwiek problemów w związku z rozegraniem meczu prosimy o wypełnienie formularza kontaktowego i dokładne opisanie sprawy.</p>
        <p>5.3 Na początku meczu prosi się obie strony o sprawdzenie konfiguracji serwera, rozpoczęcie meczu jest równoznaczne z akceptacją tych ustawień.</p>
        <p>5.4 Obu drużynom przysługuje 3 minutowa pauza, która może się odbyć tylko i wyłącznie podczas wyznaczonego czasu przed rundą lub tuż po jej zakończeniu. Przekroczenie tego czasu skutkuje dyskwalifikacją.</p>
        <p>5.5 Jeśli zostaną zauważone podejrzane akcje przeciwników, proszę o zapamiętanie, w której rundzie miało to miejsce.</p>
        <p>5.6 Mecze odbędą się w godzinach ustalonych przez Organizatora.</p>
        <p>5.7 Przegrana drużyna odpada z turnieju.</p>
        <h4>6. Postanowienia końcowe</h4>
        <p>6.1 Podczas trwania turnieju obowiązują wszystkie zasady zawarte w Regulaminie turnieju.</p>
        <p>6.2 Organizator dołoży wszelkich starań w celu zapewnienia prawidłowego przebiegu turnieju.</p>
        <p>6.3 Uczestnicy biorą udział w turnieju dobrowolnie i na własną odpowiedzialność. Organizatorzy nie ponoszą winy za szkody osobowe, rzeczowe, majątkowe przed, podczas trwania turnieju.</p>
        <p>6.4 W przypadku sporów pomiędzy graczami stroną rozstrzygającą jest Organizator Turnieju.</p>
        <p>6.5 Wszelkie kwestie sporne nie ujęte w regulaminie rozstrzygają: organizator oraz administrator, którzy mogą, lecz nie muszą uwzględnić opinii kapitanów zespołów.</p>
        Zespół EXOTIC DIVISION
        </div>";
        echo "</div>";

    } 
    else{
        header("Location: main.php");
    }
}
?>
</body>
</html>