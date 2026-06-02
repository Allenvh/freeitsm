<?php
/**
 * Polski (pl) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Raportowanie',

    'nav' => [
        'logs'    => 'Dzienniki',
        'tickets' => 'Zgłoszenia',
        'intune'  => 'Intune',
        'help'    => 'Pomoc',

        'logs_title'    => 'Dzienniki systemowe',
        'tickets_title' => 'Pulpity zgłoszeń',
        'intune_title'  => 'Pulpit Intune',
        'help_title'    => 'Pomoc',
    ],

    'landing' => [
        'heading'  => 'Raportowanie',
        'subtitle' => 'Wybierz obszar raportowania, aby rozpocząć',

        'logs_title'    => 'Dzienniki systemowe',
        'logs_desc'     => 'Przeglądaj próby logowania, importy wiadomości e-mail i inne dzienniki aktywności systemu.',
        'tickets_title' => 'Pulpity zgłoszeń',
        'tickets_desc'  => 'Pulpity KPI dotyczące wydajności zgłoszeń, czasów rozwiązywania i obciążenia zespołu.',
        'intune_title'  => 'Pulpit Intune',
        'intune_desc'   => 'Zgodność, szyfrowanie, rozkład systemów operacyjnych, trend rejestracji oraz stan ostatniej synchronizacji dla każdego zarządzanego urządzenia.',
    ],

    'logs' => [
        'heading'  => 'Dzienniki systemowe',
        'refresh'  => 'Odśwież',
        'tab_login'        => 'Logowania użytkowników',
        'tab_email_import' => 'Importy e-mail',

        'loading'        => 'Ładowanie dzienników...',
        'no_logs'        => 'Nie znaleziono dzienników',
        'load_error'     => 'Błąd ładowania dzienników: {error}',

        'col_datetime'    => 'Data/godzina',
        'col_username'    => 'Nazwa użytkownika',
        'col_status'      => 'Status',
        'col_ip'          => 'Adres IP',
        'col_user_agent'  => 'Agent użytkownika',
        'col_from'        => 'Od',
        'col_subject'     => 'Temat',
        'col_type'        => 'Typ',
        'col_attachments' => 'Załączniki',

        'status_success' => 'Sukces',
        'status_failed'  => 'Niepowodzenie',
        'unknown'        => 'Nieznany',
        'no_subject'     => '(Brak tematu)',
        'new_ticket'     => 'Nowe zgłoszenie',
        'reply'          => 'Odpowiedź',
        'none'           => 'Brak',

        'row_title'  => 'Kliknij, aby wyświetlić szczegóły JSON',

        'pagination' => 'Strona {current} z {total} (łącznie {count})',
        'prev'       => 'Poprzednia',
        'next'       => 'Następna',

        'modal_title' => 'Szczegóły dziennika (JSON)',
        'close'       => 'Zamknij',
    ],

    'tickets' => [
        'heading' => 'Pulpity zgłoszeń',
        'coming_soon' => 'Pulpity KPI oraz raportowanie dotyczące wydajności zgłoszeń, czasów rozwiązywania i obciążenia zespołu będą tutaj dostępne wkrótce.',
    ],

    'intune' => [
        'heading'      => 'Pulpit Intune',
        'loading_meta' => 'Ładowanie…',
        'refresh'      => 'Odśwież',
        'refresh_title'=> 'Odśwież dane',
        'loading_data' => 'Ładowanie danych Intune…',

        'last_sync'    => 'Ostatnia synchronizacja: {when}',
        'error'        => 'Błąd: {error}',
        'load_failed'  => 'Nie udało się załadować pulpitu: {error}',
        'no_devices_title' => 'Nie znaleziono urządzeń Intune.',
        'no_devices_body'  => 'Uruchom synchronizację Intune z modułu Zasoby, aby zaimportować urządzenia, a następnie wróć tutaj.',
        'no_data'      => 'Brak danych',
        'unknown'      => 'Nieznany',

        // KPI strip
        'kpi_total'            => 'Wszystkie urządzenia',
        'kpi_total_sub'        => 'Wszystkie zarządzane urządzenia',
        'kpi_compliant'        => 'Zgodne',
        'kpi_compliant_sub'    => '{count} z {total}',
        'kpi_encrypted'        => 'Zaszyfrowane',
        'kpi_encrypted_sub'    => '{count} z {total}',
        'kpi_stale'            => 'Nieaktualne (30+ dni)',
        'kpi_stale_sub'        => 'Brak synchronizacji w ciągu ostatnich 30 dni',
        'kpi_enrolled'         => 'Zarejestrowane (30 dni)',
        'kpi_enrolled_sub'     => 'Nowe w ciągu ostatnich 30 dni',

        'kpi_compliant_drill'  => 'Zgodne urządzenia',
        'kpi_encrypted_drill'  => 'Zaszyfrowane urządzenia',
        'kpi_stale_drill'      => 'Nieaktualne (30+ dni)',
        'kpi_enrolled_drill'   => 'Zarejestrowane w ciągu ostatnich 30 dni',

        // Widgets
        'w_compliance_title'   => 'Podział zgodności',
        'w_compliance_desc'    => 'Urządzenia według stanu zgodności',
        'w_os_title'           => 'System operacyjny',
        'w_os_desc'            => 'Urządzenia pogrupowane według systemu operacyjnego',
        'w_owner_title'        => 'Typ właściciela',
        'w_owner_desc'         => 'Urządzenia firmowe a prywatne',
        'w_manufacturers_title'=> 'Najczęstsi producenci',
        'w_manufacturers_desc' => 'Urządzenia według producenta (top 10)',
        'w_os_versions_title'  => 'Najczęstsze wersje systemu',
        'w_os_versions_desc'   => 'Najpopularniejsze kombinacje system + wersja',
        'w_last_sync_title'    => 'Okno ostatniej synchronizacji',
        'w_last_sync_desc'     => 'Jak niedawno urządzenia się zameldowały',
        'w_enrolment_title'    => 'Rejestracje (ostatnie 90 dni)',
        'w_enrolment_desc'     => 'Nowe urządzenia zarejestrowane dziennie',
        'w_encryption_title'   => 'Szyfrowanie według systemu',
        'w_encryption_desc'    => 'Zaszyfrowane a niezaszyfrowane, według systemu',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} zarejestrowanych (kliknij, aby przejść do szczegółów)',
        'drill_enrolled_on'    => 'Zarejestrowano dnia {date}',

        // Drill-down modal
        'drill_devices'        => 'Urządzenia',
        'drill_loading'        => 'Ładowanie…',
        'drill_count'          => '{count} urządzenie',
        'drill_count_plural'   => '{count} urządzeń',
        'drill_no_match'       => 'Żadne urządzenie nie pasuje do tego filtra.',
        'drill_error'          => 'Błąd: {error}',
        'drill_load_failed'    => 'Nie udało się załadować: {error}',
        'drill_page_info'      => 'Strona {current} z {total}',
        'drill_prev'           => '‹ Poprzednia',
        'drill_next'           => 'Następna ›',
        'drill_export'         => 'Eksportuj CSV',
        'drill_close'          => 'Zamknij',

        'drill_col_device'     => 'Urządzenie',
        'drill_col_user'       => 'Użytkownik',
        'drill_col_os'         => 'System',
        'drill_col_compliance' => 'Zgodność',
        'drill_col_encrypted'  => 'Zaszyfrowane',
        'drill_col_last_sync'  => 'Ostatnia synchronizacja',

        'never'                => 'Nigdy',
        'yes'                  => 'Tak',
        'no'                   => 'Nie',
    ],

    'help' => [
        'page_title' => 'Przewodnik raportowania',
        'guide'      => 'Przewodnik',

        'hero_heading' => 'Przewodnik raportowania',
        'hero_sub'     => 'Przekształć dane swojego działu wsparcia w praktyczne wnioski dzięki dziennikom, analityce i pulpitom.',

        'nav_overview'           => 'Przegląd',
        'nav_ticket_reports'     => 'Raporty zgłoszeń',
        'nav_system_logs'        => 'Dzienniki systemowe',
        'nav_understanding_data' => 'Zrozumienie danych',
        'nav_settings_filters'   => 'Ustawienia i filtry',
        'nav_tips'               => 'Szybkie wskazówki',

        // Section 1: Overview
        's1_heading' => 'Przegląd',
        's1_intro'   => 'Moduł Raportowania gromadzi w jednym miejscu wszystko, co dzieje się w Twoim dziale wsparcia. Śledź wydajność zgłoszeń, monitoruj aktywność systemu, przeglądaj próby logowania i audytuj importy wiadomości e-mail — wszystko z poziomu jednego modułu zaprojektowanego tak, aby pomóc Ci dostrzegać trendy i podejmować decyzje oparte na danych.',
        's1_card1_title' => 'Analityka zgłoszeń',
        's1_card1_body'  => 'Wizualizuj liczbę zgłoszeń, czasy rozwiązywania, zgodność z SLA i obciążenie zespołu za pomocą interaktywnych pulpitów aktualizowanych w czasie rzeczywistym.',
        's1_card2_title' => 'Dzienniki systemowe',
        's1_card2_body'  => 'Przeglądaj każdą próbę logowania, import e-mail i zdarzenie systemowe w przeszukiwalnej, filtrowalnej tabeli ze znacznikami czasu i wskaźnikami statusu.',
        's1_card3_title' => 'Śledzenie aktywności',
        's1_card3_body'  => 'Monitoruj aktywność analityków na całej platformie — kto się loguje, nad jakimi zgłoszeniami pracuje i gdzie spędzany jest czas.',
        's1_card4_title' => 'Ścieżka audytu',
        's1_card4_body'  => 'Każde działanie jest rejestrowane wraz z informacją, kto je wykonał, kiedy i co się zmieniło. Niezbędne dla zgodności, przeglądów bezpieczeństwa i rozwiązywania problemów.',

        // Section 2: Ticket reports
        's2_heading' => 'Raporty zgłoszeń',
        's2_intro'   => 'Obszar Zgłoszenia w raportowaniu zapewnia pulpity KPI, które dają jasny obraz tego, jak działa Twój dział wsparcia. Pulpity te pobierają dane bezpośrednio z rekordów zgłoszeń i prezentują je za pomocą wykresów oraz kart podsumowujących.',
        's2_card1_title' => 'Liczba zgłoszeń',
        's2_card1_body'  => 'Zobacz, ile zgłoszeń jest tworzonych, rozwiązywanych i wciąż otwartych w dowolnym okresie. Identyfikuj pracowite dni i wzorce sezonowe.',
        's2_card2_title' => 'Zgodność z SLA',
        's2_card2_body'  => 'Śledź, jaki odsetek zgłoszeń spełnia cele dotyczące odpowiedzi i rozwiązania. Przejdź do szczegółów według priorytetu lub kategorii, aby znaleźć obszary problematyczne.',
        's2_card3_title' => 'Czasy rozwiązywania',
        's2_card3_body'  => 'Mierz średni i medianowy czas rozwiązywania zgłoszeń. Porównuj między zespołami, kategoriami lub poziomami priorytetu, aby wykryć wąskie gardła.',
        's2_card4_title' => 'Obciążenie zespołu',
        's2_card4_body'  => 'Zobacz, jak zgłoszenia są rozdzielone między analityków. Zidentyfikuj, kto jest przeciążony, a kto ma możliwość przyjęcia większej ilości pracy.',
        's2_card5_title' => 'Podział na kategorie',
        's2_card5_body'  => 'Zrozum, które typy problemów generują najwięcej zgłoszeń. Wykorzystaj to do ukierunkowania szkoleń, dokumentacji lub usprawnień samoobsługi.',
        's2_card6_title' => 'Analiza trendów',
        's2_card6_body'  => 'Przeglądaj dane zgłoszeń na przestrzeni tygodni, miesięcy lub kwartałów, aby dostrzec długoterminowe trendy i zmierzyć wpływ usprawnień procesów.',
        's2_tip'         => 'Pulpity zgłoszeń są dostępne poprzez kartę Zgłoszenia w nawigacji nagłówka. Użyj filtrów zakresu dat, aby porównać różne okresy obok siebie.',

        // Section 3: System logs
        's3_heading' => 'Dzienniki systemowe',
        's3_intro'   => 'Obszar Dzienniki rejestruje wszystko, co dzieje się za kulisami Twojej instancji FreeITSM. Każda próba logowania, import e-mail i zdarzenie systemowe jest rejestrowane ze znacznikiem czasu i statusem, dzięki czemu zawsze masz pełny obraz aktywności platformy.',
        's3_badge_login'  => 'LOGOWANIE',
        's3_badge_email'  => 'E-MAIL',
        's3_badge_system' => 'SYSTEM',
        's3_badge_audit'  => 'AUDYT',
        's3_login_title'  => 'Próby logowania',
        's3_login_body'   => 'Każde udane i nieudane logowanie jest rejestrowane wraz z nazwą analityka, adresem IP i znacznikiem czasu. Nieudane próby są oznaczane na czerwono, dzięki czemu możesz szybko wykryć nieautoryzowane próby dostępu lub zablokowanych użytkowników.',
        's3_email_title'  => 'Importy e-mail',
        's3_email_body'   => 'Gdy system przetwarza przychodzące wiadomości e-mail na zgłoszenia, każdy import jest rejestrowany z adresem nadawcy, tematem oraz informacją, czy został pomyślnie przekonwertowany. Nieudane importy pokazują przyczynę, dzięki czemu możesz zbadać odrzucone lub nieprawidłowo sformułowane wiadomości.',
        's3_system_title' => 'Zdarzenia systemowe',
        's3_system_body'  => 'Procesy w tle, zaplanowane zadania, zmiany konfiguracji i aktywność API są tutaj rejestrowane. Wykorzystaj te dzienniki, aby zweryfikować, czy zautomatyzowane zadania działają poprawnie, oraz aby diagnozować problemy.',
        's3_audit_title'  => 'Wpisy audytu',
        's3_audit_body'   => 'Śledzenie zmian na poziomie pól na całej platformie. Zobacz dokładnie, kto co zmienił, kiedy i jaka była poprzednia wartość. Nieocenione dla wymogów zgodności i rozwiązywania sporów.',
        's3_step1_title' => 'Otwórz kartę Dzienniki',
        's3_step1_body'  => 'kliknij Dzienniki w nawigacji nagłówka, aby uzyskać dostęp do przeglądarki dzienników systemowych.',
        's3_step2_title' => 'Przełączaj się między typami dzienników',
        's3_step2_body'  => 'użyj paska kart u góry, aby filtrować według prób logowania, importów e-mail lub zdarzeń systemowych.',
        's3_step3_title' => 'Przejrzyj szczegóły',
        's3_step3_body'  => 'każdy wiersz pokazuje znacznik czasu, plakietkę statusu (sukces lub niepowodzenie) oraz szczegóły kontekstowe, takie jak adresy IP, tematy e-maili czy opisy zdarzeń.',
        's3_tip'         => 'Regularnie sprawdzaj dzienniki logowania pod kątem powtarzających się nieudanych prób z nieznanych adresów IP. Może to wskazywać na ataki siłowe lub przejęte poświadczenia, które wymagają natychmiastowej uwagi.',

        // Section 4: Understanding the data
        's4_heading' => 'Zrozumienie danych',
        's4_intro'   => 'Surowe dane stają się użyteczne dopiero wtedy, gdy wiesz, czego szukać. Oto kluczowe metryki, które warto obserwować, oraz sposób ich interpretacji w celu wprowadzenia rzeczywistych usprawnień w działaniu działu wsparcia.',
        's4_metric1_title' => 'Czas pierwszej odpowiedzi',
        's4_metric1_body'  => 'Jak długo użytkownicy czekają, zanim analityk potwierdzi ich zgłoszenie. Rosnący trend oznacza, że Twój zespół może być niedostatecznie obsadzony lub zgłoszenia nie są skutecznie kierowane. Cel: poniżej progu SLA.',
        's4_metric2_title' => 'Wskaźnik rozwiązywania',
        's4_metric2_body'  => 'Odsetek zgłoszeń rozwiązanych w danym okresie w stosunku do utworzonych. Jeśli wpływa więcej zgłoszeń, niż jest zamykanych, Twoje zaległości rosną i musisz zbadać przyczynę.',
        's4_metric3_title' => 'Powtarzające się kontakty',
        's4_metric3_body'  => 'Zgłoszenia ponownie otwierane lub użytkownicy zgłaszający ten sam problem wielokrotnie. Wysokie wskaźniki powtarzających się kontaktów sugerują, że przyczyna źródłowa nie jest usuwana lub że rozwiązania nie są jasno komunikowane.',
        's4_metric4_title' => 'Punkty zapalne kategorii',
        's4_metric4_body'  => 'Które kategorie generują najwięcej zgłoszeń w czasie. Nagły wzrost w danej kategorii może sygnalizować awarię systemu, wadliwą aktualizację oprogramowania lub lukę w szkoleniu użytkowników, którą należy usunąć.',
        's4_combine'     => 'Używaj tych metryk razem, a nie w izolacji. Na przykład wysoki wskaźnik rozwiązywania połączony z wysokim wskaźnikiem powtarzających się kontaktów może wskazywać, że zgłoszenia są zamykane zbyt szybko, bez rozwiązania podstawowego problemu.',
        's4_tip'         => 'Zaplanuj cotygodniowy przegląd kluczowych metryk z zespołem. Wzorce niewidoczne z dnia na dzień często stają się oczywiste, gdy spojrzeć na nie w ujęciu tygodniowym lub miesięcznym.',

        // Section 5: Settings & filters
        's5_heading' => 'Ustawienia i filtry',
        's5_intro'   => 'Zarówno przeglądarka dzienników, jak i pulpity zgłoszeń obsługują szereg filtrów, które pomagają zawęzić dane do dokładnie tych, których potrzebujesz. Skuteczne wykorzystanie filtrów zamienia ścianę danych w ukierunkowane, praktyczne informacje.',
        's5_step1_title' => 'Zakresy dat',
        's5_step1_body'  => 'filtruj dzienniki i raporty do określonego przedziału czasu. Użyj predefiniowanych zakresów (dzisiaj, ten tydzień, ten miesiąc) lub ustaw niestandardowe daty początkowe i końcowe dla precyzyjnej kontroli.',
        's5_step2_title' => 'Filtry statusu',
        's5_step2_body'  => 'w przeglądarce dzienników filtruj według statusu sukcesu lub niepowodzenia, aby szybko wyodrębnić problemy. W raportach zgłoszeń filtruj według statusu otwarte, rozwiązane lub zamknięte.',
        's5_step3_title' => 'Wyszukiwanie',
        's5_step3_body'  => 'użyj pola wyszukiwania, aby znaleźć konkretne wpisy według słowa kluczowego. W dziennikach przeszukuje to nazwy analityków, adresy IP, tematy e-maili oraz opisy zdarzeń.',
        's5_step4_title' => 'Grupowanie czasu',
        's5_step4_body'  => 'w pulpitach zgłoszeń grupuj dane według dnia, tygodnia lub miesiąca, aby zmienić szczegółowość wykresów. Widoki dzienne pokazują krótkoterminowe skoki; widoki miesięczne ujawniają długoterminowe trendy.',
        's5_step5_title' => 'Filtry działów',
        's5_step5_body'  => 'zawęź wyniki pulpitu do konkretnego działu, aby porównać wydajność różnych części organizacji.',
        's5_tip'         => 'Łącz wiele filtrów w celu ukierunkowanej analizy. Na przykład filtruj według konkretnego działu i zakresu dat, aby zobaczyć, jak niedawna zmiana procesu wpłynęła na liczbę zgłoszeń tego zespołu.',

        // Section 6: Quick tips
        's6_heading' => 'Szybkie wskazówki',
        's6_tip1_title' => 'Przeglądaj regularnie',
        's6_tip1_body'  => 'Raporty są najbardziej wartościowe, gdy są przeglądane konsekwentnie. Ustal rytm — co tydzień dla metryk operacyjnych, co miesiąc dla analizy trendów — i trzymaj się go.',
        's6_tip2_title' => 'Badaj anomalie',
        's6_tip2_body'  => 'Nagły skok lub spadek dowolnej metryki to sygnał wart zbadania. Sprawdź dzienniki pod kątem kontekstu — czy doszło do awarii systemu, wdrożenia oprogramowania lub zmiany kadrowej?',
        's6_tip3_title' => 'Porównuj okresy',
        's6_tip3_body'  => 'Użyj filtrów dat, aby porównać ten tydzień z poprzednim lub ten miesiąc z tym samym miesiącem w zeszłym roku. Porównania względne ujawniają poprawę lub regres wyraźniej niż surowe liczby.',
        's6_tip4_title' => 'Monitoruj bezpieczeństwo',
        's6_tip4_body'  => 'Miej oko na nieudane próby logowania w dziennikach systemowych. Powtarzające się niepowodzenia z tego samego adresu IP lub wobec tego samego konta mogą wskazywać na problem bezpieczeństwa wymagający eskalacji.',
    ],
];
