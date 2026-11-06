<?php
/**
 * The currency registry (GH #189) - every currency in use in the world, from
 * ISO 4217. Read only through includes/money.php; nothing else should include
 * this file.
 *
 * code => [English name, symbol, decimal places, retired]
 *
 * - Rows store the CODE ('GBP'), never a number pointing into this list: the
 *   code is already a stable international identifier, an API consumer can read
 *   it, and reordering this file can never relabel anybody's money.
 * - The English name is a FALLBACK. The browser supplies the name in the
 *   viewer's own language (Intl.DisplayNames), so 150-odd names never need to go
 *   through the translation files.
 * - Symbol: the one people locally recognise. Where several currencies share
 *   "$" or "¥" the less common one carries a prefix (A$, CA$, CN¥) so a contract
 *   priced in US dollars and one priced in Australian dollars never look alike.
 *   Where no symbol is well known, the symbol is the code itself.
 * - Decimal places are ISO's minor units, with one deliberate exception: IDR,
 *   which ISO gives 2 but nobody in Indonesia writes (#189). An administrator can
 *   override the decimals for any currency in System -> Currencies anyway.
 * - Retired (true): no longer issued, so never OFFERED in a dropdown - but still
 *   recognised, so an old contract in Bulgarian lev keeps displaying and saving.
 *   Add a currency here when a country retires it rather than deleting the row.
 *
 * Excluded on purpose: ISO's fund codes (BOV, CLF, COU, MXV, USN, UYI, UYW, CHE,
 * CHW), precious metals (XAU, XAG, XPD, XPT), and the testing/bond codes (XTS,
 * XXX, XBA-XBD, XDR, XSU, XUA). Nobody prices a software licence in gold.
 *
 * When ISO publishes a new currency: add one row, ship it. No schema change.
 */
return [
    'AED' => ['UAE dirham', 'AED', 2],
    'AFN' => ['Afghan afghani', '؋', 2],
    'ALL' => ['Albanian lek', 'Lek', 2],
    'AMD' => ['Armenian dram', '֏', 2],
    'AOA' => ['Angolan kwanza', 'Kz', 2],
    'ARS' => ['Argentine peso', 'AR$', 2],
    'AUD' => ['Australian dollar', 'A$', 2],
    'AWG' => ['Aruban florin', 'Afl.', 2],
    'AZN' => ['Azerbaijani manat', '₼', 2],
    'BAM' => ['Bosnia-Herzegovina convertible mark', 'KM', 2],
    'BBD' => ['Barbadian dollar', 'Bds$', 2],
    'BDT' => ['Bangladeshi taka', '৳', 2],
    'BHD' => ['Bahraini dinar', 'BD', 3],
    'BIF' => ['Burundian franc', 'FBu', 0],
    'BMD' => ['Bermudan dollar', 'BMD', 2],
    'BND' => ['Brunei dollar', 'B$', 2],
    'BOB' => ['Bolivian boliviano', 'Bs', 2],
    'BRL' => ['Brazilian real', 'R$', 2],
    'BSD' => ['Bahamian dollar', 'BSD', 2],
    'BTN' => ['Bhutanese ngultrum', 'Nu.', 2],
    'BWP' => ['Botswanan pula', 'P', 2],
    'BYN' => ['Belarusian rouble', 'Br', 2],
    'BZD' => ['Belize dollar', 'BZ$', 2],
    'CAD' => ['Canadian dollar', 'CA$', 2],
    'CDF' => ['Congolese franc', 'FC', 2],
    'CHF' => ['Swiss franc', 'CHF', 2],
    'CLP' => ['Chilean peso', 'CLP$', 0],
    'CNY' => ['Chinese yuan', 'CN¥', 2],
    'COP' => ['Colombian peso', 'COL$', 2],
    'CRC' => ['Costa Rican colón', '₡', 2],
    'CUP' => ['Cuban peso', 'CUP', 2],
    'CVE' => ['Cape Verdean escudo', 'Esc', 2],
    'CZK' => ['Czech koruna', 'Kč', 2],
    'DJF' => ['Djiboutian franc', 'Fdj', 0],
    'DKK' => ['Danish krone', 'kr.', 2],
    'DOP' => ['Dominican peso', 'RD$', 2],
    'DZD' => ['Algerian dinar', 'DA', 2],
    'EGP' => ['Egyptian pound', 'E£', 2],
    'ERN' => ['Eritrean nakfa', 'Nfk', 2],
    'ETB' => ['Ethiopian birr', 'Br', 2],
    'EUR' => ['Euro', '€', 2],
    'FJD' => ['Fijian dollar', 'FJ$', 2],
    'FKP' => ['Falkland Islands pound', 'FK£', 2],
    'GBP' => ['British pound', '£', 2],
    'GEL' => ['Georgian lari', '₾', 2],
    'GHS' => ['Ghanaian cedi', 'GH₵', 2],
    'GIP' => ['Gibraltar pound', 'GIP', 2],
    'GMD' => ['Gambian dalasi', 'D', 2],
    'GNF' => ['Guinean franc', 'FG', 0],
    'GTQ' => ['Guatemalan quetzal', 'Q', 2],
    'GYD' => ['Guyanaese dollar', 'G$', 2],
    'HKD' => ['Hong Kong dollar', 'HK$', 2],
    'HNL' => ['Honduran lempira', 'L', 2],
    'HTG' => ['Haitian gourde', 'G', 2],
    'HUF' => ['Hungarian forint', 'Ft', 2],
    'IDR' => ['Indonesian rupiah', 'Rp', 0],
    'ILS' => ['Israeli new shekel', '₪', 2],
    'INR' => ['Indian rupee', '₹', 2],
    'IQD' => ['Iraqi dinar', 'IQD', 3],
    'IRR' => ['Iranian rial', 'IRR', 2],
    'ISK' => ['Icelandic króna', 'kr', 0],
    'JMD' => ['Jamaican dollar', 'J$', 2],
    'JOD' => ['Jordanian dinar', 'JD', 3],
    'JPY' => ['Japanese yen', '¥', 0],
    'KES' => ['Kenyan shilling', 'KSh', 2],
    'KGS' => ['Kyrgystani som', 'KGS', 2],
    'KHR' => ['Cambodian riel', '៛', 2],
    'KMF' => ['Comorian franc', 'CF', 0],
    'KPW' => ['North Korean won', 'KPW', 2],
    'KRW' => ['South Korean won', '₩', 0],
    'KWD' => ['Kuwaiti dinar', 'KD', 3],
    'KYD' => ['Cayman Islands dollar', 'CI$', 2],
    'KZT' => ['Kazakhstani tenge', '₸', 2],
    'LAK' => ['Laotian kip', '₭', 2],
    'LBP' => ['Lebanese pound', 'LBP', 2],
    'LKR' => ['Sri Lankan rupee', 'Rs', 2],
    'LRD' => ['Liberian dollar', 'L$', 2],
    'LSL' => ['Lesotho loti', 'LSL', 2],
    'LYD' => ['Libyan dinar', 'LD', 3],
    'MAD' => ['Moroccan dirham', 'DH', 2],
    'MDL' => ['Moldovan leu', 'MDL', 2],
    'MGA' => ['Malagasy ariary', 'Ar', 2],
    'MKD' => ['Macedonian denar', 'den', 2],
    'MMK' => ['Myanmar kyat', 'K', 2],
    'MNT' => ['Mongolian tugrik', '₮', 2],
    'MOP' => ['Macanese pataca', 'MOP$', 2],
    'MRU' => ['Mauritanian ouguiya', 'UM', 2],
    'MUR' => ['Mauritian rupee', 'Rs', 2],
    'MVR' => ['Maldivian rufiyaa', 'Rf', 2],
    'MWK' => ['Malawian kwacha', 'MK', 2],
    'MXN' => ['Mexican peso', 'MX$', 2],
    'MYR' => ['Malaysian ringgit', 'RM', 2],
    'MZN' => ['Mozambican metical', 'MT', 2],
    'NAD' => ['Namibian dollar', 'N$', 2],
    'NGN' => ['Nigerian naira', '₦', 2],
    'NIO' => ['Nicaraguan córdoba', 'C$', 2],
    'NOK' => ['Norwegian krone', 'kr', 2],
    'NPR' => ['Nepalese rupee', 'Rs', 2],
    'NZD' => ['New Zealand dollar', 'NZ$', 2],
    'OMR' => ['Omani rial', 'OMR', 3],
    'PAB' => ['Panamanian balboa', 'B/.', 2],
    'PEN' => ['Peruvian sol', 'S/', 2],
    'PGK' => ['Papua New Guinean kina', 'K', 2],
    'PHP' => ['Philippine peso', '₱', 2],
    'PKR' => ['Pakistani rupee', 'Rs', 2],
    'PLN' => ['Polish złoty', 'zł', 2],
    'PYG' => ['Paraguayan guarani', '₲', 0],
    'QAR' => ['Qatari riyal', 'QAR', 2],
    'RON' => ['Romanian leu', 'lei', 2],
    'RSD' => ['Serbian dinar', 'RSD', 2],
    'RUB' => ['Russian rouble', '₽', 2],
    'RWF' => ['Rwandan franc', 'RF', 0],
    'SAR' => ['Saudi riyal', 'SAR', 2],
    'SBD' => ['Solomon Islands dollar', 'SI$', 2],
    'SCR' => ['Seychellois rupee', 'SR', 2],
    'SDG' => ['Sudanese pound', 'SDG', 2],
    'SEK' => ['Swedish krona', 'kr', 2],
    'SGD' => ['Singapore dollar', 'S$', 2],
    'SHP' => ['St. Helena pound', 'SHP', 2],
    'SLE' => ['Sierra Leonean leone', 'Le', 2],
    'SOS' => ['Somali shilling', 'SOS', 2],
    'SRD' => ['Surinamese dollar', 'SRD', 2],
    'SSP' => ['South Sudanese pound', 'SSP', 2],
    'STN' => ['São Tomé and Príncipe dobra', 'Db', 2],
    'SVC' => ['Salvadoran colón', 'SVC', 2],
    'SYP' => ['Syrian pound', 'SYP', 2],
    'SZL' => ['Swazi lilangeni', 'E', 2],
    'THB' => ['Thai baht', '฿', 2],
    'TJS' => ['Tajikistani somoni', 'SM', 2],
    'TMT' => ['Turkmenistani manat', 'TMT', 2],
    'TND' => ['Tunisian dinar', 'DT', 3],
    'TOP' => ['Tongan paʻanga', 'T$', 2],
    'TRY' => ['Turkish lira', '₺', 2],
    'TTD' => ['Trinidad and Tobago dollar', 'TT$', 2],
    'TWD' => ['New Taiwan dollar', 'NT$', 2],
    'TZS' => ['Tanzanian shilling', 'TSh', 2],
    'UAH' => ['Ukrainian hryvnia', '₴', 2],
    'UGX' => ['Ugandan shilling', 'USh', 0],
    'USD' => ['US dollar', '$', 2],
    'UYU' => ['Uruguayan peso', '$U', 2],
    'UZS' => ['Uzbekistani som', 'UZS', 2],
    'VED' => ['Venezuelan bolívar (digital)', 'VED', 2],
    'VES' => ['Venezuelan bolívar', 'Bs.S', 2],
    'VND' => ['Vietnamese dong', '₫', 0],
    'VUV' => ['Vanuatu vatu', 'VT', 0],
    'WST' => ['Samoan tala', 'WS$', 2],
    'XAF' => ['Central African CFA franc', 'FCFA', 0],
    'XCD' => ['East Caribbean dollar', 'EC$', 2],
    'XCG' => ['Caribbean guilder', 'Cg', 2],
    'XOF' => ['West African CFA franc', 'CFA', 0],
    'XPF' => ['CFP franc', 'XPF', 0],
    'YER' => ['Yemeni rial', 'YER', 2],
    'ZAR' => ['South African rand', 'R', 2],
    'ZMW' => ['Zambian kwacha', 'ZK', 2],
    'ZWG' => ['Zimbabwe gold', 'ZiG', 2],

    // ---- Retired: recognised for old records, never offered ----------------
    'ANG' => ['Netherlands Antillean guilder', 'NAf.', 2, true],   // -> XCG, 2025
    'BGN' => ['Bulgarian lev', 'лв', 2, true],                     // -> EUR, 2026
    'CUC' => ['Cuban convertible peso', 'CUC', 2, true],           // 2021
    'EEK' => ['Estonian kroon', 'kr', 2, true],                    // -> EUR, 2011
    'HRK' => ['Croatian kuna', 'kn', 2, true],                     // -> EUR, 2023
    'LTL' => ['Lithuanian litas', 'Lt', 2, true],                  // -> EUR, 2015
    'LVL' => ['Latvian lats', 'Ls', 2, true],                      // -> EUR, 2014
    'MRO' => ['Mauritanian ouguiya (1973-2017)', 'UM', 2, true],   // -> MRU
    'SLL' => ['Sierra Leonean leone (1964-2022)', 'Le', 2, true],  // -> SLE
    'STD' => ['São Tomé and Príncipe dobra (1977-2017)', 'Db', 2, true], // -> STN
    'VEF' => ['Venezuelan bolívar (2008-2018)', 'Bs', 2, true],    // -> VES
    'ZWL' => ['Zimbabwean dollar (2009-2024)', 'ZWL', 2, true],    // -> ZWG
];
