<?php
/**
 * Português (Brasil) (pt-BR) — Reporting module strings.
 * Missing keys fall back to lang/en/reporting.php per-key.
 */
return [
    'title' => 'Relatórios',

    'nav' => [
        'logs'    => 'Logs',
        'tickets' => 'Tickets',
        'intune'  => 'Intune',
        'help'    => 'Ajuda',

        'logs_title'    => 'Logs do sistema',
        'tickets_title' => 'Painéis de tickets',
        'intune_title'  => 'Painel do Intune',
        'help_title'    => 'Ajuda',
    ],

    'landing' => [
        'heading'  => 'Relatórios',
        'subtitle' => 'Escolha uma área de relatórios para começar',

        'logs_title'    => 'Logs do sistema',
        'logs_desc'     => 'Veja tentativas de login, importações de e-mail e outros logs de atividade do sistema.',
        'tickets_title' => 'Painéis de tickets',
        'tickets_desc'  => 'Painéis de KPI para desempenho de tickets, tempos de resolução e carga de trabalho da equipe.',
        'intune_title'  => 'Painel do Intune',
        'intune_desc'   => 'Conformidade, criptografia, distribuição de SO, tendência de inscrição e integridade da última sincronização em todos os dispositivos gerenciados.',
    ],

    'logs' => [
        'heading'  => 'Logs do sistema',
        'refresh'  => 'Atualizar',
        'tab_login'        => 'Logins de usuários',
        'tab_email_import' => 'Importações de e-mail',

        'loading'        => 'Carregando logs...',
        'no_logs'        => 'Nenhum log encontrado',
        'load_error'     => 'Erro ao carregar logs: {error}',

        'col_datetime'    => 'Data/hora',
        'col_username'    => 'Nome de usuário',
        'col_status'      => 'Status',
        'col_ip'          => 'Endereço IP',
        'col_user_agent'  => 'Agente de usuário',
        'col_from'        => 'De',
        'col_subject'     => 'Assunto',
        'col_type'        => 'Tipo',
        'col_attachments' => 'Anexos',

        'status_success' => 'Sucesso',
        'status_failed'  => 'Falhou',
        'unknown'        => 'Desconhecido',
        'no_subject'     => '(Sem assunto)',
        'new_ticket'     => 'Novo ticket',
        'reply'          => 'Resposta',
        'none'           => 'Nenhum',

        'row_title'  => 'Clique para ver os detalhes em JSON',

        'pagination' => 'Página {current} de {total} ({count} no total)',
        'prev'       => 'Anterior',
        'next'       => 'Próximo',

        'modal_title' => 'Detalhes do log (JSON)',
        'close'       => 'Fechar',
    ],

    'tickets' => [
        'heading' => 'Painéis de tickets',
        'coming_soon' => 'Painéis de KPI e relatórios para desempenho de tickets, tempos de resolução e carga de trabalho da equipe estarão disponíveis aqui em breve.',
    ],

    'intune' => [
        'heading'      => 'Painel do Intune',
        'loading_meta' => 'Carregando…',
        'refresh'      => 'Atualizar',
        'refresh_title'=> 'Atualizar dados',
        'loading_data' => 'Carregando dados do Intune…',

        'last_sync'    => 'Última sincronização: {when}',
        'error'        => 'Erro: {error}',
        'load_failed'  => 'Falha ao carregar o painel: {error}',
        'no_devices_title' => 'Nenhum dispositivo do Intune encontrado.',
        'no_devices_body'  => 'Execute uma sincronização do Intune no módulo Ativos para importar dispositivos e depois volte aqui.',
        'no_data'      => 'Sem dados',
        'unknown'      => 'Desconhecido',

        // KPI strip
        'kpi_total'            => 'Total de dispositivos',
        'kpi_total_sub'        => 'Todos os dispositivos gerenciados',
        'kpi_compliant'        => 'Em conformidade',
        'kpi_compliant_sub'    => '{count} de {total}',
        'kpi_encrypted'        => 'Criptografados',
        'kpi_encrypted_sub'    => '{count} de {total}',
        'kpi_stale'            => 'Desatualizados (30+ dias)',
        'kpi_stale_sub'        => 'Sem sincronização nos últimos 30 dias',
        'kpi_enrolled'         => 'Inscritos (30 dias)',
        'kpi_enrolled_sub'     => 'Novos nos últimos 30 dias',

        'kpi_compliant_drill'  => 'Dispositivos em conformidade',
        'kpi_encrypted_drill'  => 'Dispositivos criptografados',
        'kpi_stale_drill'      => 'Desatualizados (30+ dias)',
        'kpi_enrolled_drill'   => 'Inscritos nos últimos 30 dias',

        // Widgets
        'w_compliance_title'   => 'Detalhamento de conformidade',
        'w_compliance_desc'    => 'Dispositivos por estado de conformidade',
        'w_os_title'           => 'Sistema operacional',
        'w_os_desc'            => 'Dispositivos agrupados por SO',
        'w_owner_title'        => 'Tipo de proprietário',
        'w_owner_desc'         => 'Dispositivos corporativos vs. pessoais',
        'w_manufacturers_title'=> 'Principais fabricantes',
        'w_manufacturers_desc' => 'Dispositivos por fabricante (top 10)',
        'w_os_versions_title'  => 'Principais versões de SO',
        'w_os_versions_desc'   => 'Combinações mais comuns de SO + versão',
        'w_last_sync_title'    => 'Janela da última sincronização',
        'w_last_sync_desc'     => 'Há quanto tempo os dispositivos se registraram',
        'w_enrolment_title'    => 'Inscrições (últimos 90 dias)',
        'w_enrolment_desc'     => 'Novos dispositivos inscritos por dia',
        'w_encryption_title'   => 'Criptografia por SO',
        'w_encryption_desc'    => 'Criptografados vs. não criptografados, por SO',

        // Chart tooltips / labels
        'tooltip_enrolled'     => '{count} inscritos (clique para detalhar)',
        'drill_enrolled_on'    => 'Inscrito em {date}',

        // Drill-down modal
        'drill_devices'        => 'Dispositivos',
        'drill_loading'        => 'Carregando…',
        'drill_count'          => '{count} dispositivo',
        'drill_count_plural'   => '{count} dispositivos',
        'drill_no_match'       => 'Nenhum dispositivo corresponde a este filtro.',
        'drill_error'          => 'Erro: {error}',
        'drill_load_failed'    => 'Falha ao carregar: {error}',
        'drill_page_info'      => 'Página {current} de {total}',
        'drill_prev'           => '‹ Anterior',
        'drill_next'           => 'Próximo ›',
        'drill_export'         => 'Exportar CSV',
        'drill_close'          => 'Fechar',

        'drill_col_device'     => 'Dispositivo',
        'drill_col_user'       => 'Usuário',
        'drill_col_os'         => 'SO',
        'drill_col_compliance' => 'Conformidade',
        'drill_col_encrypted'  => 'Criptografado',
        'drill_col_last_sync'  => 'Última sincronização',

        'never'                => 'Nunca',
        'yes'                  => 'Sim',
        'no'                   => 'Não',
    ],

    'help' => [
        'page_title' => 'Guia de relatórios',
        'guide'      => 'Guia',

        'hero_heading' => 'Guia de relatórios',
        'hero_sub'     => 'Transforme os dados da sua central de serviços em insights acionáveis com logs, análises e painéis.',

        'nav_overview'           => 'Visão geral',
        'nav_ticket_reports'     => 'Relatórios de tickets',
        'nav_system_logs'        => 'Logs do sistema',
        'nav_understanding_data' => 'Entendendo os dados',
        'nav_settings_filters'   => 'Configurações e filtros',
        'nav_tips'               => 'Dicas rápidas',

        // Section 1: Overview
        's1_heading' => 'Visão geral',
        's1_intro'   => 'O módulo de Relatórios reúne tudo o que acontece na sua central de serviços em um só lugar. Acompanhe o desempenho dos tickets, monitore a atividade do sistema, revise tentativas de login e audite importações de e-mail — tudo a partir de um único módulo projetado para ajudar você a identificar tendências e tomar decisões baseadas em dados.',
        's1_card1_title' => 'Análise de tickets',
        's1_card1_body'  => 'Visualize o volume de tickets, tempos de resolução, conformidade com SLA e carga de trabalho da equipe por meio de painéis interativos atualizados em tempo real.',
        's1_card2_title' => 'Logs do sistema',
        's1_card2_body'  => 'Revise cada tentativa de login, importação de e-mail e evento do sistema em uma tabela pesquisável e filtrável com carimbos de data/hora e indicadores de status.',
        's1_card3_title' => 'Acompanhamento de atividade',
        's1_card3_body'  => 'Monitore a atividade dos analistas em toda a plataforma — quem está fazendo login, em quais tickets se está trabalhando e onde o tempo está sendo gasto.',
        's1_card4_title' => 'Trilha de auditoria',
        's1_card4_body'  => 'Cada ação é registrada com quem a realizou, quando e o que mudou. Essencial para conformidade, revisões de segurança e solução de problemas.',

        // Section 2: Ticket reports
        's2_heading' => 'Relatórios de tickets',
        's2_intro'   => 'A área de Tickets dos relatórios fornece painéis de KPI que dão a você uma visão clara de como sua central de serviços está se saindo. Esses painéis extraem dados diretamente dos seus registros de tickets e os apresentam por meio de gráficos e cartões de resumo.',
        's2_card1_title' => 'Volume de tickets',
        's2_card1_body'  => 'Veja quantos tickets são criados, resolvidos e ainda estão abertos em qualquer período. Identifique dias movimentados e padrões sazonais.',
        's2_card2_title' => 'Conformidade com SLA',
        's2_card2_body'  => 'Acompanhe qual porcentagem de tickets cumpre suas metas de resposta e resolução. Detalhe por prioridade ou categoria para encontrar áreas problemáticas.',
        's2_card3_title' => 'Tempos de resolução',
        's2_card3_body'  => 'Meça o tempo médio e mediano para resolver tickets. Compare entre equipes, categorias ou níveis de prioridade para identificar gargalos.',
        's2_card4_title' => 'Carga de trabalho da equipe',
        's2_card4_body'  => 'Veja como os tickets estão distribuídos entre os analistas. Identifique quem está sobrecarregado e quem tem capacidade para assumir mais trabalho.',
        's2_card5_title' => 'Detalhamento por categoria',
        's2_card5_body'  => 'Entenda quais tipos de problemas geram mais tickets. Use isso para direcionar treinamento, documentação ou melhorias de autoatendimento.',
        's2_card6_title' => 'Análise de tendências',
        's2_card6_body'  => 'Veja os dados de tickets ao longo de semanas, meses ou trimestres para identificar tendências de longo prazo e medir o impacto das melhorias de processo.',
        's2_tip'         => 'Os painéis de tickets são acessados pela aba Tickets na navegação do cabeçalho. Use filtros de período para comparar diferentes intervalos lado a lado.',

        // Section 3: System logs
        's3_heading' => 'Logs do sistema',
        's3_intro'   => 'A área de Logs captura tudo o que acontece nos bastidores da sua instância do FreeITSM. Cada tentativa de login, importação de e-mail e evento do sistema é registrado com um carimbo de data/hora e status, para que você sempre tenha uma visão completa da atividade da plataforma.',
        's3_badge_login'  => 'LOGIN',
        's3_badge_email'  => 'E-MAIL',
        's3_badge_system' => 'SISTEMA',
        's3_badge_audit'  => 'AUDITORIA',
        's3_login_title'  => 'Tentativas de login',
        's3_login_body'   => 'Cada login bem-sucedido e malsucedido é registrado com o nome do analista, o endereço IP e o carimbo de data/hora. As tentativas malsucedidas são sinalizadas em vermelho para que você possa identificar rapidamente tentativas de acesso não autorizado ou usuários bloqueados.',
        's3_email_title'  => 'Importações de e-mail',
        's3_email_body'   => 'Quando o sistema processa e-mails recebidos em tickets, cada importação é registrada com o endereço do remetente, a linha de assunto e se foi convertida com sucesso. Importações com falha mostram o motivo, para que você possa investigar mensagens devolvidas ou malformadas.',
        's3_system_title' => 'Eventos do sistema',
        's3_system_body'  => 'Processos em segundo plano, tarefas agendadas, alterações de configuração e atividade da API são todos capturados aqui. Use esses logs para verificar se os trabalhos automatizados estão sendo executados corretamente e para diagnosticar problemas.',
        's3_audit_title'  => 'Entradas de auditoria',
        's3_audit_body'   => 'Rastreamento de alterações em nível de campo em toda a plataforma. Veja exatamente quem alterou o quê, quando e qual era o valor anterior. Inestimável para requisitos de conformidade e resolução de disputas.',
        's3_step1_title' => 'Abra a aba Logs',
        's3_step1_body'  => 'clique em Logs na navegação do cabeçalho para acessar o visualizador de logs do sistema.',
        's3_step2_title' => 'Alterne entre os tipos de log',
        's3_step2_body'  => 'use a barra de abas na parte superior para filtrar por tentativas de login, importações de e-mail ou eventos do sistema.',
        's3_step3_title' => 'Revise os detalhes',
        's3_step3_body'  => 'cada linha mostra um carimbo de data/hora, um selo de status (sucesso ou falha) e detalhes contextuais como endereços IP, assuntos de e-mail ou descrições de eventos.',
        's3_tip'         => 'Verifique os logs de login regularmente em busca de tentativas malsucedidas repetidas de endereços IP desconhecidos. Isso pode indicar ataques de força bruta ou credenciais comprometidas que exigem atenção imediata.',

        // Section 4: Understanding the data
        's4_heading' => 'Entendendo os dados',
        's4_intro'   => 'Dados brutos só se tornam úteis quando você sabe o que procurar. Aqui estão as principais métricas a observar e como interpretá-las para gerar melhorias reais nas operações da sua central de serviços.',
        's4_metric1_title' => 'Tempo de primeira resposta',
        's4_metric1_body'  => 'Quanto tempo os usuários esperam antes de um analista reconhecer o ticket deles. Uma tendência de alta aqui significa que sua equipe pode estar com poucos funcionários ou que os tickets não estão sendo encaminhados de forma eficaz. Meta: abaixo do seu limite de SLA.',
        's4_metric2_title' => 'Taxa de resolução',
        's4_metric2_body'  => 'A porcentagem de tickets resolvidos dentro de um determinado período em comparação com os criados. Se entram mais tickets do que saem, seu acúmulo está crescendo e você precisa investigar a causa.',
        's4_metric3_title' => 'Contatos repetidos',
        's4_metric3_body'  => 'Tickets reabertos ou usuários relatando o mesmo problema várias vezes. Altas taxas de contato repetido sugerem que a causa raiz não está sendo tratada ou que as soluções não estão sendo comunicadas com clareza.',
        's4_metric4_title' => 'Pontos críticos por categoria',
        's4_metric4_body'  => 'Quais categorias geram mais tickets ao longo do tempo. Um pico em uma categoria específica pode sinalizar um sistema com falha, uma atualização de software ruim ou uma lacuna no treinamento dos usuários que precisa ser tratada.',
        's4_combine'     => 'Use essas métricas em conjunto, e não isoladamente. Por exemplo, uma alta taxa de resolução combinada com uma alta taxa de contato repetido pode indicar que os tickets estão sendo fechados rápido demais, sem resolver o problema subjacente.',
        's4_tip'         => 'Agende uma revisão semanal das suas principais métricas com a equipe. Padrões invisíveis no dia a dia muitas vezes se tornam óbvios quando vistos em uma cadência semanal ou mensal.',

        // Section 5: Settings & filters
        's5_heading' => 'Configurações e filtros',
        's5_intro'   => 'Tanto o visualizador de logs quanto os painéis de tickets oferecem uma variedade de filtros para ajudar você a restringir exatamente os dados de que precisa. O uso eficaz dos filtros transforma uma parede de dados em informações direcionadas e acionáveis.',
        's5_step1_title' => 'Períodos',
        's5_step1_body'  => 'filtre logs e relatórios para uma janela de tempo específica. Use intervalos predefinidos (hoje, esta semana, este mês) ou defina datas de início e fim personalizadas para um controle preciso.',
        's5_step2_title' => 'Filtros de status',
        's5_step2_body'  => 'no visualizador de logs, filtre por status de sucesso ou falha para isolar problemas rapidamente. Nos relatórios de tickets, filtre por status aberto, resolvido ou fechado.',
        's5_step3_title' => 'Busca',
        's5_step3_body'  => 'use a caixa de busca para encontrar entradas específicas por palavra-chave. Nos logs, isso pesquisa em nomes de analistas, endereços IP, assuntos de e-mail e descrições de eventos.',
        's5_step4_title' => 'Agrupamento por tempo',
        's5_step4_body'  => 'nos painéis de tickets, agrupe os dados por dia, semana ou mês para alterar a granularidade dos seus gráficos. Visualizações diárias mostram picos de curto prazo; visualizações mensais revelam tendências de longo prazo.',
        's5_step5_title' => 'Filtros de departamento',
        's5_step5_body'  => 'restrinja os resultados do painel a um departamento específico para comparar o desempenho entre diferentes partes da organização.',
        's5_tip'         => 'Combine vários filtros para uma análise direcionada. Por exemplo, filtre por um departamento específico e um período para ver como uma mudança recente de processo afetou o volume de tickets daquela equipe.',

        // Section 6: Quick tips
        's6_heading' => 'Dicas rápidas',
        's6_tip1_title' => 'Revise regularmente',
        's6_tip1_body'  => 'Os relatórios são mais valiosos quando revisados de forma consistente. Defina uma cadência — semanal para métricas operacionais, mensal para análise de tendências — e mantenha-a.',
        's6_tip2_title' => 'Investigue anomalias',
        's6_tip2_body'  => 'Um pico ou queda repentina em qualquer métrica é um sinal que vale a pena investigar. Verifique os logs em busca de contexto — houve uma interrupção do sistema, um lançamento de software ou uma mudança de pessoal?',
        's6_tip3_title' => 'Compare períodos',
        's6_tip3_body'  => 'Use filtros de data para comparar esta semana com a semana passada, ou este mês com o mesmo mês do ano passado. Comparações relativas revelam melhoria ou regressão com mais clareza do que números brutos.',
        's6_tip4_title' => 'Monitore a segurança',
        's6_tip4_body'  => 'Fique de olho nas tentativas de login malsucedidas nos logs do sistema. Falhas repetidas do mesmo endereço IP ou contra a mesma conta podem indicar uma preocupação de segurança que precisa ser escalada.',
    ],
];
