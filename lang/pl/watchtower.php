<?php
/**
 * Polski (pl) — Watchtower module strings.
 * Missing keys fall back to lang/en/watchtower.php per-key.
 */
return [
    'title' => 'Watchtower',

    'nav' => [
        'dashboard' => 'Pulpit',
        'help'      => 'Pomoc',
    ],

    'dashboard' => [
        'heading'      => 'Przegląd uwagi',
        'refresh'      => 'Odśwież',
        'updated'      => 'Zaktualizowano {time}',
    ],

    // Per-module card names shown in the card header (links to each module).
    'cards' => [
        'morning_checks' => 'Poranne kontrole',
        'tickets'        => 'Zgłoszenia',
        'changes'        => 'Zmiany',
        'calendar'       => 'Kalendarz',
        'service_status' => 'Status usług',
        'contracts'      => 'Umowy',
        'knowledge'      => 'Baza wiedzy',
        'assets'         => 'Zasoby',
        'tasks'          => 'Zadania',
    ],

    // Morning Checks card.
    'mc' => [
        'metric_done' => 'Wykonane',
        'metric_ok'   => 'OK',
        'metric_warn' => 'Ostrzeżenie',
        'metric_fail' => 'Błąd',
        'not_started'      => 'Kontrole nie rozpoczęte dzisiaj',
        'pending'          => '{count} kontroli nadal oczekuje',
        'failed'           => '{count} kontroli zakończonych niepowodzeniem',
        'warnings'         => '{count} kontroli z ostrzeżeniami',
        'all_passing'      => 'Wszystkie kontrole zakończone i zaliczone',
    ],

    // Tickets card.
    'tickets' => [
        'metric_open'   => 'Otwarte',
        'metric_new'    => 'Nowe',
        'metric_active' => 'Aktywne',
        'metric_hold'   => 'Wstrzymane',
        'urgent_high'   => '<span class="wt-attention-bold">{count}</span> zgłoszeń o priorytecie pilnym/wysokim',
        'unassigned'    => '<span class="wt-attention-bold">{count}</span> nieprzypisanych zgłoszeń',
        'paused_one'    => '<span class="wt-attention-bold">{count}</span> zgłoszenie wstrzymane ponad {hours}h (zegar SLA zatrzymany)',
        'paused_many'   => '<span class="wt-attention-bold">{count}</span> zgłoszeń wstrzymanych ponad {hours}h (zegar SLA zatrzymany)',
        'all_clear'     => 'Brak pilnych pozycji',
    ],

    // Changes card.
    'changes' => [
        'metric_next_7d' => 'Najbliższe 7 dni',
        'metric_active'  => 'Aktywne',
        'metric_pending' => 'Oczekujące',
        'awaiting'       => '<span class="wt-attention-bold">{count}</span> zmian oczekujących na zatwierdzenie',
        'in_progress'    => '{count} zmian w toku',
        'scheduled'      => '{count} zmian zaplanowanych na ten tydzień',
        'all_clear'      => 'Brak nadchodzących zmian',
    ],

    // Calendar card.
    'calendar' => [
        'metric_today' => 'Dzisiaj',
        'metric_week'  => 'Ten tydzień',
        'all_day'      => 'Cały dzień',
        'no_events'    => 'Brak wydarzeń dzisiaj',
    ],

    // Service Status card.
    'service' => [
        'all_operational' => 'Wszystkie systemy działają',
        'active_incidents' => '<span class="wt-attention-bold">{count}</span> aktywnych incydentów',
    ],

    // Contracts card.
    'contracts' => [
        'metric_30d'     => '30 dni',
        'metric_90d'     => '90 dni',
        'metric_notices' => 'Wypowiedzenia',
        'expiring'       => '<span class="wt-attention-bold">{count}</span> umów wygasających w ciągu 30 dni',
        'notices'        => '<span class="wt-attention-bold">{count}</span> zbliżających się okresów wypowiedzenia',
        'all_clear'      => 'Brak umów wymagających uwagi',
    ],

    // Knowledge card.
    'knowledge' => [
        'overdue'         => '<span class="wt-attention-bold">{count}</span> artykułów zaległych do przeglądu',
        'published_week'  => 'Opublikowane w tym tygodniu',
        'up_to_date'      => 'Baza wiedzy aktualna',
    ],

    // Assets card.
    'assets' => [
        'metric_total'    => 'Łącznie',
        'metric_offline'  => 'Offline',
        'metric_warranty' => 'Gwarancja',
        'warranty'        => '<span class="wt-attention-bold">{count}</span> zasobów z gwarancją wygasłą lub wygasającą w ciągu {days} dni',
        'offline'         => '<span class="wt-attention-bold">{count}</span> zasobów niewidzianych od 7+ dni',
        'all_active'      => 'Wszystkie zasoby ostatnio aktywne',
    ],

    // Tasks card.
    'tasks' => [
        'metric_todo'   => 'Do zrobienia',
        'metric_active' => 'Aktywne',
        'overdue'       => '<span class="wt-attention-bold">{count}</span> zaległych zadań',
        'due_today'     => '<span class="wt-attention-bold">{count}</span> z terminem na dzisiaj',
        'all_clear'     => 'Brak zaległych zadań',
    ],

    // Help guide.
    'help' => [
        'page_title'   => 'Przewodnik Watchtower',
        'sidebar_label' => 'Przewodnik',
        'hero_title'   => 'Przewodnik Watchtower',
        'hero_subtitle' => 'Ujednolicony pulpit uwagi pokazujący pozycje wymagające działania ze wszystkich modułów na jeden rzut oka.',

        'nav_overview'  => 'Przegląd',
        'nav_layout'    => 'Układ pulpitu',
        'nav_dots'      => 'Rozumienie kropek statusu',
        'nav_cards'     => 'Objaśnienie kart modułów',
        'nav_refresh'   => 'Automatyczne odświeżanie',
        'nav_tips'      => 'Szybkie wskazówki',

        // Section 1 — Overview
        's1_title' => 'Przegląd',
        's1_intro' => 'Watchtower to Twój pojedynczy pulpit dla operacji IT. Zamiast otwierać każdy moduł osobno, aby sprawdzić pilne pozycje, Watchtower zbiera najważniejsze informacje z każdego modułu w jednym pulpicie. Na jeden rzut oka widzisz, co wymaga uwagi, co działa sprawnie i na czym skupić swój czas.',
        's1_feat1_title' => 'Tablica uwagi',
        's1_feat1_desc'  => 'Zobacz, co wymaga Twojej uwagi we wszystkich modułach w jednym miejscu. Poranne kontrole, zgłoszenia, zmiany, wydarzenia w kalendarzu, status usług, umowy, artykuły bazy wiedzy i zasoby — wszystko podsumowane na jednym ekranie.',
        's1_feat2_title' => 'Status oznaczony kolorami',
        's1_feat2_desc'  => 'Każda karta modułu wyświetla zieloną, bursztynową lub czerwoną kropkę statusu dla błyskawicznej oceny. Na jeden rzut oka rozpoznasz, które obszary są w dobrym stanie, które wymagają uwagi, a które wymagają natychmiastowego działania.',
        's1_feat3_title' => 'Automatyczne odświeżanie',
        's1_feat3_desc'  => 'Pulpit automatycznie odświeża się co 5 minut, dzięki czemu informacje pozostają aktualne bez żadnego ręcznego działania. Zostaw Watchtower otwarty, a sam będzie się aktualizował w tle.',
        's1_feat4_title' => 'Przejście jednym kliknięciem',
        's1_feat4_desc'  => 'Przejdź bezpośrednio do dowolnego modułu z jego karty. Każda nazwa modułu jest klikalnym linkiem, który przenosi Cię prosto do odpowiedniego obszaru, dzięki czemu możesz reagować na problemy bez szukania właściwej strony.',

        // Section 2 — Dashboard layout
        's2_title' => 'Układ pulpitu',
        's2_p1' => 'Pulpit Watchtower wykorzystuje responsywną 3-kolumnową siatkę kart modułów. Na mniejszych ekranach siatka dostosowuje się do 2 kolumn lub pojedynczej kolumny, więc działa na każdym urządzeniu. Nad siatką znajduje się pasek tytułu z przyciskiem odświeżania oraz znacznikiem czasu „Zaktualizowano”, pokazującym, kiedy dane zostały ostatnio pobrane.',
        's2_p2' => 'Każda karta w siatce ma spójną strukturę, dzięki czemu możesz je szybko przeglądać:',
        's2_diagram_name'   => 'Nazwa modułu',
        's2_diagram_open'   => 'OTWARTE',
        's2_diagram_active' => 'AKTYWNE',
        's2_diagram_hold'   => 'WSTRZYMANE',
        's2_diagram_clear'  => 'Wszystko w porządku — brak pilnych pozycji',
        's2_field_icon'    => '<strong>Kolorowa ikona</strong> &mdash; mała kwadratowa ikona w kolorze motywu modułu (turkusowy dla Porannych kontroli, niebieski dla Zgłoszeń itd.), dzięki czemu możesz natychmiast rozpoznać każdą kartę.',
        's2_field_name'    => '<strong>Nazwa modułu</strong> &mdash; klikalny link prowadzący bezpośrednio do tego modułu. Kliknij, aby przejść prosto do działania.',
        's2_field_dot'     => '<strong>Kropka statusu</strong> &mdash; zielona, bursztynowa lub czerwona kropka w prawym górnym rogu pokazująca ogólny poziom pilności dla tego modułu.',
        's2_field_metrics' => '<strong>Kluczowe metryki</strong> &mdash; duże liczby podsumowujące najważniejsze wartości (np. otwarte zgłoszenia, ukończone kontrole, wygasające umowy).',
        's2_field_attention' => '<strong>Pozycje wymagające uwagi</strong> &mdash; oznaczone kolorami wiersze komunikatów wyróżniające to, co konkretnie wymaga Twojej uwagi w danym module.',
        's2_tip' => 'Układ karty jest zaprojektowany do przeglądania, a nie do dogłębnej analizy. Użyj Watchtower, aby zidentyfikować, które moduły wymagają Twojej uwagi, a następnie przejdź do samego modułu po pełne szczegóły.',

        // Section 3 — Status dots
        's3_title' => 'Rozumienie kropek statusu',
        's3_intro' => 'Każda karta modułu wyświetla kropkę statusu w swoim nagłówku. Ta kropka stanowi natychmiastowy wskaźnik wizualny tego, czy dany obszar Twoich operacji IT wymaga uwagi. Kolor jest ustalany automatycznie na podstawie danych zwracanych z każdego modułu.',
        's3_green_label' => 'Zielona',
        's3_green_desc'  => 'Wszystko w porządku. Nie jest wymagane żadne działanie. Moduł jest w dobrym stanie, bez nierozwiązanych problemów ani pozycji wymagających uwagi.',
        's3_green_examples' => '<strong>Przykłady:</strong> wszystkie poranne kontrole zaliczone, brak pilnych zgłoszeń, wszystkie systemy działają, brak umów wygasających wkrótce.',
        's3_amber_label' => 'Bursztynowa',
        's3_amber_desc'  => 'Coś wymaga uwagi, ale nie jest krytyczne. Są pozycje, które powinieneś przejrzeć przy okazji, ale nic się nie pali.',
        's3_amber_examples' => '<strong>Przykłady:</strong> kontrole z ostrzeżeniami, nieprzypisane zgłoszenia, zmiany oczekujące na zatwierdzenie, umowy wygasające w ciągu 90 dni.',
        's3_red_label' => 'Czerwona',
        's3_red_desc'  => 'Pilne pozycje wymagają natychmiastowego działania. Coś zakończyło się niepowodzeniem, jest zaległe lub jest krytycznie dotknięte i wymaga niezwłocznego zajęcia się tym.',
        's3_red_examples' => '<strong>Przykłady:</strong> poranne kontrole nie rozpoczęte lub zakończone niepowodzeniem, zgłoszenia o priorytecie pilnym/wysokim, poważne awarie usług, umowy wygasające w ciągu 30 dni.',
        's3_tip' => 'Pomyśl o kropkach jak o sygnalizacji świetlnej. Zielona oznacza, że możesz zajmować się swoimi sprawami, bursztynowa oznacza przegląd przy okazji, a czerwona oznacza, że masz przerwać to, co robisz, i zbadać sprawę. Celem jest utrzymanie wszystkich kropek na zielono.',

        // Section 4 — Module cards explained
        's4_title' => 'Objaśnienie kart modułów',
        's4_intro' => 'Watchtower monitoruje osiem modułów. Każda karta jest dostosowana, aby pokazywać najistotniejsze informacje dla danego obszaru. Oto, co wyświetla każda karta i co wyzwala kolor jej kropki statusu.',
        's4_mc_title'    => 'Poranne kontrole',
        's4_mc_desc'     => 'Pokazuje postęp ukończenia (np. 8/10 wykonanych) oraz liczbę wyników OK, Ostrzeżenie i Błąd. Pozycje wymagające uwagi sygnalizują, gdy kontrole nie zostały rozpoczęte lub gdy któraś zakończyła się niepowodzeniem.',
        's4_mc_triggers' => '<strong>Czerwona:</strong> kontrole nie rozpoczęte dzisiaj lub jakiekolwiek kontrole zakończone niepowodzeniem. <strong>Bursztynowa:</strong> kontrole nieukończone lub obecne ostrzeżenia. <strong>Zielona:</strong> wszystkie kontrole ukończone i zaliczone.',
        's4_tk_title'    => 'Zgłoszenia',
        's4_tk_desc'     => 'Wyświetla łączną liczbę otwartych zgłoszeń w podziale na Nowe, Aktywne i Wstrzymane. Pozycje wymagające uwagi wyróżniają zgłoszenia o priorytecie pilnym/wysokim oraz wszelkie nieprzypisane.',
        's4_tk_triggers' => '<strong>Czerwona:</strong> istnieją zgłoszenia o priorytecie pilnym lub wysokim. <strong>Bursztynowa:</strong> obecne nieprzypisane zgłoszenia. <strong>Zielona:</strong> brak pilnych pozycji ani nieprzypisanych zgłoszeń.',
        's4_ch_title'    => 'Zmiany',
        's4_ch_desc'     => 'Pokazuje liczbę zmian zaplanowanych w ciągu najbliższych 7 dni, ile jest obecnie w toku oraz ile oczekuje na zatwierdzenie. Pozycje wymagające uwagi sygnalizują niezatwierdzone i aktywne zmiany.',
        's4_ch_triggers' => '<strong>Bursztynowa:</strong> zmiany oczekujące na zatwierdzenie. <strong>Zielona:</strong> brak niezatwierdzonych zmian.',
        's4_cal_title'    => 'Kalendarz',
        's4_cal_desc'     => 'Wyświetla liczbę wydarzeń dzisiaj i w tym tygodniu. Jeśli są wydarzenia na dzisiaj, są wymienione wraz z ich godzinami (lub „Cały dzień” dla wydarzeń całodniowych).',
        's4_cal_triggers' => '<strong>Bursztynowa:</strong> wydarzenia zaplanowane na dzisiaj. <strong>Zielona:</strong> brak wydarzeń dzisiaj.',
        's4_ss_title'    => 'Status usług',
        's4_ss_desc'     => 'Pokazuje liczbę aktywnych incydentów i wymienia dotknięte usługi wraz z odznakami poziomu wpływu (Poważna awaria, Częściowa awaria, Pogorszenie, Konserwacja). Gdy wszystko jest w dobrym stanie, pojawia się zielony baner „Wszystkie systemy działają”.',
        's4_ss_triggers' => '<strong>Czerwona:</strong> poważna lub częściowa awaria dowolnej usługi. <strong>Bursztynowa:</strong> status pogorszenia lub konserwacji. <strong>Zielona:</strong> wszystkie systemy działają.',
        's4_ct_title'    => 'Umowy',
        's4_ct_desc'     => 'Wyświetla umowy wygasające w ciągu 30 dni, w ciągu 90 dni oraz zbliżające się okresy wypowiedzenia. Pozycje wymagające uwagi ostrzegają o nadchodzących wygaśnięciach i zbliżających się terminach wypowiedzenia.',
        's4_ct_triggers' => '<strong>Czerwona:</strong> umowy wygasające w ciągu 30 dni. <strong>Bursztynowa:</strong> umowy wygasające w ciągu 90 dni lub zbliżające się okresy wypowiedzenia. <strong>Zielona:</strong> brak umów wymagających uwagi.',
        's4_kb_title'    => 'Baza wiedzy',
        's4_kb_desc'     => 'Pokazuje liczbę artykułów zaległych do przeglądu i wymienia niedawno opublikowane artykuły z tego tygodnia. Gdy żaden przegląd nie jest zaległy, a baza wiedzy jest aktualna, karta wyświetla komunikat o braku problemów.',
        's4_kb_triggers' => '<strong>Bursztynowa:</strong> artykuły zaległe do przeglądu. <strong>Zielona:</strong> baza wiedzy aktualna.',
        's4_as_title'    => 'Zasoby',
        's4_as_desc'     => 'Wyświetla łączną liczbę śledzonych zasobów oraz ile z nich nie było widzianych od 7 lub więcej dni. Pomaga to zidentyfikować urządzenia, które mogą być offline, wycofane z eksploatacji lub zgubione.',
        's4_as_triggers' => '<strong>Bursztynowa:</strong> zasoby niewidziane od 7+ dni. <strong>Zielona:</strong> wszystkie zasoby ostatnio aktywne.',

        // Section 5 — Auto-refresh
        's5_title' => 'Automatyczne i ręczne odświeżanie',
        's5_intro' => 'Watchtower jest zaprojektowany jako pasywne narzędzie monitorujące, które możesz zostawić otwarte w karcie przeglądarki przez cały dzień. Pulpit utrzymuje się w aktualnym stanie dzięki cyklom automatycznego odświeżania.',
        's5_step1' => '<strong>Automatyczne odświeżanie</strong> &mdash; pulpit pobiera świeże dane ze wszystkich modułów co 5 minut. Nie musisz przeładowywać strony ani niczego klikać; karty i kropki statusu aktualizują się po cichu w tle.',
        's5_step2' => '<strong>Ręczne odświeżanie</strong> &mdash; kliknij przycisk <strong>Odśwież</strong> w prawym górnym rogu, aby natychmiast pobrać najnowsze dane. Ikona przycisku obraca się podczas trwania żądania, potwierdzając, że nowe dane są wczytywane.',
        's5_step3' => '<strong>Znacznik czasu aktualizacji</strong> &mdash; obok przycisku odświeżania znacznik czasu pokazuje, kiedy dane zostały ostatnio pobrane (np. „Zaktualizowano 09:15”). Mówi to dokładnie, jak aktualne są wyświetlane informacje.',
        's5_tip' => 'Trzymaj Watchtower otwarty w dedykowanej karcie przeglądarki dla pasywnego monitorowania. 5-minutowy cykl odświeżania oznacza, że zawsze masz niemal bieżący widok swoich operacji IT bez konieczności ręcznego sprawdzania każdego modułu.',

        // Section 6 — Quick tips
        's6_title' => 'Szybkie wskazówki',
        's6_tip1_title' => 'Zacznij swój dzień tutaj',
        's6_tip1_desc'  => 'Otwieraj Watchtower jako pierwszą rzecz każdego ranka, aby uzyskać szybki przegląd operacyjny. W kilka sekund zobaczysz, czy poranne kontrole są wykonane, czy któreś zgłoszenia są pilne i czy wszystkie usługi są w dobrym stanie.',
        's6_tip2_title' => 'Najpierw czerwone kropki',
        's6_tip2_desc'  => 'Zajmij się czerwonymi kropkami statusu przed czymkolwiek innym. Wskazują one pilne pozycje wymagające natychmiastowej uwagi &mdash; nieudane kontrole, zgłoszenia o wysokim priorytecie lub awarie usług aktywnie wpływające na użytkowników.',
        's6_tip3_title' => 'Kliknij, aby przejść',
        's6_tip3_desc'  => 'Kliknij dowolną nazwę modułu na karcie, aby przejść prosto do tego modułu. Nie ma potrzeby korzystania z menu głównego ani nawigacji wafel &mdash; Watchtower działa jako bezpośredni skrót do miejsca, gdzie potrzebna jest uwaga.',
        's6_tip4_title' => 'Naciśnij Odśwież po najnowsze dane',
        's6_tip4_desc'  => 'Choć pulpit odświeża się automatycznie co 5 minut, możesz kliknąć przycisk Odśwież w dowolnej chwili, gdy chcesz mieć absolutnie najnowsze dane. Przydatne po rozwiązaniu problemu, aby potwierdzić, że kropka statusu się zmieniła.',
        's6_tip5_title' => 'Wykorzystaj go na spotkaniach zespołu',
        's6_tip5_desc'  => 'Wyświetl Watchtower na ekranie podczas spotkań stand-up lub przeglądów operacyjnych. Oznaczone kolorami kropki ułatwiają omówienie, które obszary wymagają uwagi, i przypisanie odpowiedzialności za bursztynowe lub czerwone pozycje.',
        's6_tip6_title' => 'Zielony oznacza brak problemów',
        's6_tip6_desc'  => 'Gdy każda kropka na pulpicie jest zielona, Twoje operacje IT są w dobrej kondycji. Brak pilnych zgłoszeń, brak nieudanych kontroli, brak wygasających umów, a wszystkie usługi działają. To właśnie jest cel.',
    ],
];
