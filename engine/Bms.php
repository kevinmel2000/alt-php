<?php

class Bms {
    // application id, from CMS
    const APPID = 1;
    const GMAP_KEY              = 'AIzaSyAX7GHdu7OQVWRVPhF72JzS9RC8s4Y95PU';

    // module name
    const MODULE_ASSET          = 'asset';
    const MODULE_FINANCE        = 'finance';
    const MODULE_FOUNDATION     = 'foundation';
    const MODULE_MAINTENANCE    = 'maintenance';
    const MODULE_TENANCY        = 'tenancy';
    const MODULE_MICE           = 'mice';
    const MODULE_ENTERPRISE     = 'enterprise';

    public static $modules  = array(
        self::MODULE_FOUNDATION  => 'Foundation',
        self::MODULE_TENANCY     => 'Tenant Management',
        self::MODULE_ASSET       => 'Asset',
        self::MODULE_MAINTENANCE => 'Maintenance',
        self::MODULE_FINANCE     => 'Finance',
        self::MODULE_MICE        => 'MICE',
        self::MODULE_ENTERPRISE  => 'Enterprise'
    );

    // registry
    const ACTIVITY_ADDITIONALSERVICE    = 'ADS';
    const ACTIVITY_BAST                 = 'WOA';
    const ACTIVITY_EQUIPMENTPLACEMENT   = 'EQP';
    const ACTIVITY_WORKORDER            = 'WOR';
    const ACTIVITY_MICE                 = 'MIC';
    const ACTIVITY_OVERTIME             = 'OVT';
    const ACTIVITY_REGISTRATION         = 'REG';
    const ACTIVITY_RENTCONTRACT         = 'REC';
    const ACTIVITY_STRATATITLECONTRACT  = 'STC';
    const ACTIVITY_UTILITIES            = 'UTI';
    const ACTIVITY_BILLING              = 'BIL';

    const STATUS_YES            = 1;
    const STATUS_NO             = 0;
    const STATUS_TRUE           = 1;
    const STATUS_FALSE          = 0;
    const STATUS_NEW            = 'NEW';
    const STATUS_DRAFT          = 'DRF';
    const STATUS_FINAL          = 'FNL';
    const STATUS_APPROVED       = 'APR'; // Value dipake di database buat kondisi view. kalo ganti jangan lupa ganti yang di database nya juga
    const STATUS_REJECTED       = 'REJ';
    const STATUS_ONPROGRESS     = 'OPR';
    const STATUS_DONE           = 'DON';
    const STATUS_OCCUPIED       = 'OCC';
    const STATUS_AVAILABLE      = 'AVL';
//    const STATUS_MEDIAINVOICE   = 'MDI';
    const STATUS_INVOICE        = 'INV';
    const STATUS_PAID           = 'PAI';
    const STATUS_CLOSED         = 'CLO';
    const STATUS_INSPECTION     = 'INS';
    const STATUS_WORKORDER      = 'WOR';
    const STATUS_BOOKED         = 'BOO';

    const CHECKLIST_STATUS_NOTREQUIRED      = 0;
    const CHECKLIST_STATUS_REQUIRED         = 1;
    const CHECKLIST_STATUS_REQUIREDWITHFILE = 2;

    const OWNERSHIP_OWN         = 'OWN';
    const OWNERSHIP_STRATATITLE = 'STT';
    const OWNERSHIP_RENT        = 'REN';

    const PURPOSE_OFFICE        = 'OFC';
    const PURPOSE_RESIDENCE     = 'RES';
    const PURPOSE_WAREHOUSE     = 'WRH';
    const PURPOSE_OTHERS        = 'OTH';

    const PRIORITY_LOW          = '1LO';
    const PRIORITY_NORMAL       = '2NO';
    const PRIORITY_HIGH         = '3HI';
    const PRIORITY_URGENT       = '4UR';

    const ORIGIN_INTERNAL       = 'INT';
    const ORIGIN_EXTERNAL       = 'EXT';

    const RATE_FLAT             = 'FLA';
    const RATE_PROGRESIF        = 'PRO';

    const TYPE_INDIVIDUAL       = 'IND';
    const TYPE_CORPORATE        = 'COR';

    const IDTYPE_DRIVINGLICENSE = 'SIM';
    const IDTYPE_RESIDENCEIDCARD= 'KTP';
    const IDTYPE_PASSPORT       = 'PAS';

    const CHANNEL_TICKET        = 'TIC';
    const CHANNEL_APPLICATION   = 'APP';

    const MAINTENANCE_EXECUTOR_NONE     = 'EXN';
    const MAINTENANCE_EXECUTOR_SELF     = 'EXS';
    const MAINTENANCE_EXECUTOR_VENDOR   = 'EXV';

    const MAINTENANCE_TYPE_PREVENTIVE   = 'PRE';
    const MAINTENANCE_TYPE_CORRECTIVE   = 'COR';

    // setting
    //const SETTING_REGNUMBER         = 'regnumber';
    const SETTING_MICENUMBER        = 'micenumber';
    const SETTING_INVOICENUMBER     = 'invoicenumber';
    const SETTING_DUEDATETYPE       = 'duedatetype';
    const SETTING_DUEDATE           = 'duedate';
    const SETTING_TRANSNUMBER       = 'transnumber';
    const SETTING_PPN               = 'ppn';
    const SETTING_SATUANSEWA        = 'satuansewa';
    const SETTING_CONTRACTPERIOD    = 'contractperiod';
    const SETTING_INVOICEDATE       = 'invoicedate';
    const SETTING_NOKONTRAKSWAKELOLA= 'nokontrakswakelola';

    const SETTING_TRANS_ALWAYS_APPROVE     = 'trans_always_approve';
    const SETTING_ACCOUNT_LENGTH           = 'account_length';
    const SETTING_ACCOUNT_RETAINED_EARNING = 'account_retained_earning';
    const SETTING_ACCOUNT_CURRENT_EARNING  = 'account_current_earning';
    const SETTING_NOREF_LENGTH             = 'noref_length';

    public static $contract_period  = array(
        0                           => 'Mengikuti bulan',
        30                          => '30 hari dalam 1 bulan',
        31                          => '31 hari dalam 1 bulan',
    );

    const DUEDATE_CONTRACTDATE      = 'contractdate';
    const DUEDATE_SETDATE           = 'setdate';
    public static $duedate_type  = array(
        self::DUEDATE_CONTRACTDATE  => 'Mengikuti tanggal kontrak',
        self::DUEDATE_SETDATE       => 'Set tanggal tetap setiap bulan',
    );

    const ASSET_DEPRECIATE_TYPE_0 = '0';
    const ASSET_DEPRECIATE_TYPE_1 = '1';
    const ASSET_DEPRECIATE_TYPE_2 = '2';
    const ASSET_DEPRECIATE_TYPE_3 = '3';

    const SATUANSEWA_PERJAM         = 'JAM';
    const SATUANSEWA_PERHARI        = 'HRI';
    const SATUANSEWA_PERM2_PERHARI  = 'HRM';
    const SATUANSEWA_PERBULAN       = 'BLN';
    const SATUANSEWA_PERM2_PERBULAN = 'BLM';
    const SATUANSEWA_PERTAHUN       = 'THN';
    const SATUANSEWA_PERM2_PERTAHUN = 'THM';

    public static $satuan_sewa  = array(
        self::SATUANSEWA_PERHARI        => 'Per hari',
        self::SATUANSEWA_PERM2_PERHARI  => 'Per m2/hari',
        self::SATUANSEWA_PERBULAN       => 'Per bulan',
        self::SATUANSEWA_PERM2_PERBULAN => 'Per m2/bulan',
        self::SATUANSEWA_PERTAHUN       => 'Per tahun',
        self::SATUANSEWA_PERM2_PERTAHUN => 'Per m2/tahun'
    );

    const NOTIFICATION_CATEGORY_TODO        = 'TOD';
    const NOTIFICATION_CATEGORY_REMINDER    = 'REM';

    const NOTIFICATION_TYPE_NEWREQUESTINSPECTION        = 'newrequestinspection';
    const NOTIFICATION_TYPE_NEWWORKORDER                = 'newworkorder';

    const REPORT_TYPE_SURATPERINGATAN = 'SP';

    const SURATPERINGATAN_TYPE_1 = '1';
    const SURATPERINGATAN_TYPE_2 = '2';
    const SURATPERINGATAN_TYPE_3 = '3';

    const PLACEHOLDER_TEMPLATE  = 'T';
    const PLACEHOLDER_COMPONENT = 'C';
    const PLACEHOLDER_REPORT    = 'R';

    const CONTRACT_UNIT                 = 'unit';
    const CONTRACT_ADDITIONALSERVICE    = 'additionalservice';
    const CONTRACT_MICE                 = 'mice';

    const RENT_TYPE_DAILY               = 'DAI';
    const RENT_TYPE_HOURLY              = 'HOU';

    const TRANSACTION_TYPE_INVOICE  = 'I';
    const TRANSACTION_TYPE_PAYMENT  = 'P';

    const FINANCE_TRANSACTION_REFERENCE_GENERAL     = 'GEN';
    const FINANCE_TRANSACTION_REFERENCE_CRE         = 'CRE';
    const FINANCE_TRANSACTION_REFERENCE_CWD         = 'CWD';

    const FINANCE_CASHFLOWTYPE_INVESTING_ACTIVITIES     = 'IA';
    const FINANCE_CASHFLOWTYPE_OPERATING_ACTIVITIES     = 'OA';
    const FINANCE_CASHFLOWTYPE_FINANCING_ACTIVITIES     = 'FA';
    const FINANCE_CASHFLOWTYPE_CASH_BANK                = 'CB';

    const FINANCE_CONTACT_TYPE_CUSTOMER          = 1;
    const FINANCE_CONTACT_TYPE_SUPPLIER          = 2;
    const FINANCE_CONTACT_TYPE_CUSTOMERSUPPLIER  = 3;
    const FINANCE_CONTACT_TYPE_OTHERS            = 0;

    const PAYMENT_METHOD_CASH        = 'CH';
    const PAYMENT_METHOD_CREDITCARD  = 'CC';
    const PAYMENT_METHOD_DEBITCARD   = 'DC';
    const PAYMENT_METHOD_TRANSFER    = 'TF';

    public static function registry(){
        $rc = new ReflectionClass(get_class());

        $registry = array(
            // array
            'modules'               => self::$modules,

            // setting
            'setting'               => array(
                self::MODULE_TENANCY            => array(
                    /*self::SETTING_REGNUMBER     => array(
                        'label'             => 'Nomor Registrasi',
                        'form'              => 'select',
                        'value'             => array(
                            1               => 'Auto generate',
                            0               => 'Nomor registrasi sendiri'
                        )
                    ),*/
                    self::SETTING_MICENUMBER    => array(
                        'label'             => 'Nomor Order MICE',
                        'form'              => 'select',
                        'value'             => array(
                            1               => 'Auto generate',
                            0               => 'Nomor order sendiri'
                        )
                    ),
                    self::SETTING_INVOICENUMBER => array(
                        'label'             => 'Nomor Media Invoice',
                        'form'              => 'select',
                        'value'             => array(
                            1               => 'Auto generate',
                            0               => 'Nomor invoice sendiri'
                        )
                    ),
                    self::SETTING_TRANSNUMBER   => array(
                        'label'             => 'Nomor Transaksi Lain',
                        'form'              => 'select',
                        'value'             => array(
                            1               => 'Auto generate',
                            0               => 'Nomor transaksi lain sendiri'
                        )
                    ),
                    self::SETTING_PPN           => array(
                        'label'             => 'Default PPn',
                        'form'              => 'select',
                        'value'             => array(
                            1               => 'Include PPn',
                            0               => 'Exclude PPn'
                        )
                    ),
                    self::SETTING_CONTRACTPERIOD=> array(
                        'label'             => 'Periode Kontrak',
                        'form'              => 'select',
                        'value'             => self::$contract_period
                    ),
                    self::SETTING_INVOICEDATE   => array(
                        'label'             => 'Tanggal Invoice',
                        'form'              => 'text',
                        'value'             => ''
                    ),
                    self::SETTING_DUEDATETYPE   => array(
                        'label'             => 'Tipe Tanggal Jatuh Tempo',
                        'form'              => 'select',
                        'value'             => self::$duedate_type
                    ),
                    self::SETTING_DUEDATE   => array(
                        'label'             => 'Tanggal Jatuh Tempo',
                        'form'              => 'text',
                        'value'             => ''
                    )
                ),
                self::MODULE_MAINTENANCE        => array(
                    self::SETTING_NOKONTRAKSWAKELOLA    => array(
                        'label'             => 'Nomor Kontrak Swakelola',
                        'form'              => 'select',
                        'value'             => array(
                            1               => 'Auto generate',
                            0               => 'Nomor kontrak sendiri'
                        )
                    )
                ),
                self::MODULE_FINANCE            => array(
                    self::SETTING_TRANS_ALWAYS_APPROVE     => array(
                        'isonsetup'         => 0,
                        'label'             => 'Transaction Always Approve',
                        'form'              => 'select',
                        'value'             => array(
                            1               => 'Ya',
                            0               => 'Tidak'
                        ),
                        'importantacc'      => false

                    ),
                    self::SETTING_ACCOUNT_LENGTH    => array(
                        'isonsetup'         => 1,
                        'label'             => 'Panjang Nomor Akun',
                        'form'              => 'text',
                        'maxlength'         => 2,
                        'value'             => '',
                        'importantacc'      => false
                    ),
                    self::SETTING_ACCOUNT_RETAINED_EARNING => array(
                        'isonsetup'         => 1,
                        'label'             => 'Laba Ditahan',
                        'form'              => 'select',
                        'value'             => '',
                        'importantacc'      => true
                    ),
                    self::SETTING_ACCOUNT_CURRENT_EARNING   => array(
                        'isonsetup'         => 1,
                        'label'             => 'Laba Tahun Berjalan',
                        'form'              => 'select',
                        'value'             => '',
                        'importantacc'      => true
                    ),
                    self::SETTING_NOREF_LENGTH           => array(
                        'isonsetup'         => 0,
                        'label'             => 'Panjang Nomor Referensi',
                        'form'              => 'text',
                        'maxlength'         => 2,
                        'value'             => '',
                        'importantacc'      => false
                    )
                )
            ),

            'billing_status'       => array(
                self::STATUS_NEW          => 'Baru',
//                self::STATUS_MEDIAINVOICE => 'Media Invoice',
                self::STATUS_INVOICE      => 'Invoice',
                self::STATUS_PAID         => 'Sudah Dibayar'
            ),

            'billing_activity'       => array(
                self::ACTIVITY_STRATATITLECONTRACT   => 'Kontrak Strata Title',
                self::ACTIVITY_RENTCONTRACT          => 'Kontrak Sewa',
                self::ACTIVITY_ADDITIONALSERVICE     => 'Layanan Tambahan',
                self::ACTIVITY_EQUIPMENTPLACEMENT    => 'Penempatan Alat',
                self::ACTIVITY_MICE                  => 'MICE',
                self::ACTIVITY_OVERTIME              => 'Overtime',
                self::ACTIVITY_UTILITIES             => 'Utilities Tenant'
            ),

            'contract_type'             => array(
                self::CONTRACT_UNIT                 => 'Kontrak',
                self::CONTRACT_ADDITIONALSERVICE    => 'Kontrak Layanan Tambahan',
            ),

            'contract_status'            => array(
                self::STATUS_DRAFT       => 'Draft',
                self::STATUS_FINAL       => 'Final',
                self::STATUS_APPROVED    => 'Approved',
                self::STATUS_REJECTED    => 'Rejected',
                self::STATUS_CLOSED      => 'Closed'
            ),

            'mice_status'               => array(
                self::STATUS_DRAFT       => 'Draft',
                self::STATUS_BOOKED      => 'Booked',
                self::STATUS_FINAL       => 'Final',
                self::STATUS_CLOSED      => 'Closed'
            ),

            'checklist_status'      => array(
                self::STATUS_ONPROGRESS  => 'Dalam Pengerjaan',
                self::STATUS_DONE        => 'Selesai'
            ),

            'checklist_required_status' => array(
                self::CHECKLIST_STATUS_REQUIRED => 'Ya',
                self::CHECKLIST_STATUS_REQUIREDWITHFILE => 'Ya, dengan file',
                self::CHECKLIST_STATUS_NOTREQUIRED => 'Tidak'
            ),

            'contract_ownership'=> array(
                self::OWNERSHIP_RENT         => 'Sewa',
                self::OWNERSHIP_STRATATITLE  => 'Strata Title'
            ),

            'unit_ownership'=> array(
                self::OWNERSHIP_OWN          => 'Hak Milik',
                self::OWNERSHIP_STRATATITLE  => 'Strata Title'
            ),

            'rentable_status'       => array(
                self::STATUS_TRUE        => 'Disewakan',
                self::STATUS_FALSE       => 'Tidak Disewakan'
            ),

            'availability_status'   => array(
                self::STATUS_AVAILABLE   => 'Tersedia',
                self::STATUS_OCCUPIED    => 'Terisi'
            ),

            'activity'              => array(
                self::MODULE_TENANCY    => array(
                    self::ACTIVITY_STRATATITLECONTRACT   => 'Kontrak Strata Title',
                    self::ACTIVITY_RENTCONTRACT          => 'Kontrak Sewa',
                    self::ACTIVITY_ADDITIONALSERVICE     => 'Layanan Tambahan',
                    self::ACTIVITY_OVERTIME              => 'Overtime'
                ),
                self::MODULE_MAINTENANCE    => array(
                    self::ACTIVITY_WORKORDER            => 'Pekerjaan'
                ),
                self::MODULE_MICE    => array(
                    self::ACTIVITY_MICE            => 'Event MICE'
                )
            ),

            'tenant_origin'         => array(
                self::ORIGIN_INTERNAL        => 'Internal',
                self::ORIGIN_EXTERNAL        => 'Eksternal'
            ),

            'tenant_type'           => array(
                self::TYPE_INDIVIDUAL        => 'Individu',
                self::TYPE_CORPORATE         => 'Badan Hukum'
            ),

            'finance_contacttype'           => array(
                self::FINANCE_CONTACT_TYPE_CUSTOMER         => 'Customer',
                self::FINANCE_CONTACT_TYPE_SUPPLIER         => 'Supplier',
                self::FINANCE_CONTACT_TYPE_CUSTOMERSUPPLIER => 'Customer & Supplier',
                self::FINANCE_CONTACT_TYPE_OTHERS           => 'Lain-Lain',
            ),

            'yes_no_status'         => array(
                self::STATUS_YES             => 'Ya',
                self::STATUS_NO              => 'Tidak'
            ),

            'rent_purpose'          => array(
                self::PURPOSE_OFFICE         => 'Kantor',
                self::PURPOSE_RESIDENCE      => 'Hunian',
                self::PURPOSE_WAREHOUSE      => 'Gudang',
                self::PURPOSE_OTHERS         => 'Lain-Lain'
            ),

            'idtype'                => array(
                self::IDTYPE_DRIVINGLICENSE  => 'SIM',
                self::IDTYPE_RESIDENCEIDCARD => 'KTP',
                self::IDTYPE_PASSPORT        => 'Paspor'
            ),

            'depreciatetype'        => array(
                self::ASSET_DEPRECIATE_TYPE_0 => 'Straight Line',
                self::ASSET_DEPRECIATE_TYPE_1 => 'Double Declining Balance',
                self::ASSET_DEPRECIATE_TYPE_2 => 'Sum of The Years',
                self::ASSET_DEPRECIATE_TYPE_3 => 'MACRS'
            ),

            'contract_rate_type'    => array(
                self::RATE_FLAT          => 'Flat',
                self::RATE_PROGRESIF     => 'Progresif'
            ),

            'maintenance_type'       => array(
                self::MAINTENANCE_TYPE_PREVENTIVE    => 'Preventive',
                self::MAINTENANCE_TYPE_CORRECTIVE    => 'Corrective'
            ),

            'maintenance_request_status'    => array(
                self::STATUS_DRAFT       => 'Draft',
                self::STATUS_APPROVED    => 'Disetujui',
                self::STATUS_REJECTED    => 'Ditolak'
            ),

            'maintenance_inspection_status'    => array(
                self::STATUS_DRAFT       => 'Draft',
                self::STATUS_APPROVED    => 'Disetujui',
                self::STATUS_REJECTED    => 'Ditolak',
                self::STATUS_CLOSED      => 'Selesai'
            ),

            'maintenance_plan_status'    => array(
                self::STATUS_DRAFT       => 'Draft',
                self::STATUS_FINAL       => 'Final',
                self::STATUS_APPROVED    => 'Disetujui'
            ),

            'maintenance_inspection_executor'      => array(
                self::MAINTENANCE_EXECUTOR_NONE      => 'Tidak Perlu Perbaikan',
                self::MAINTENANCE_EXECUTOR_SELF      => 'Swakelola',
                self::MAINTENANCE_EXECUTOR_VENDOR    => 'Vendor'
            ),

            'maintenance_workorder_status'        => array(
                self::STATUS_DRAFT      => 'Draft',
                self::STATUS_ONPROGRESS => 'Dalam Pengerjaan',
                self::STATUS_CLOSED     => 'Closed'
            ),

            'maintenance_executor'       => array(
                self::MAINTENANCE_EXECUTOR_SELF      => 'Swakelola',
                self::MAINTENANCE_EXECUTOR_VENDOR    => 'Vendor'
            ),

            'priority'                  => array(
                self::PRIORITY_LOW       => 'Rendah',
                self::PRIORITY_NORMAL    => 'Sedang',
                self::PRIORITY_HIGH      => 'Tinggi',
                self::PRIORITY_URGENT    => 'Penting'
            ),

            'satuan_sewa'       => self::$satuan_sewa,

            // ngasih label jangan panjang-panjang
            'notification_type'     => array(
                self::NOTIFICATION_TYPE_NEWREQUESTINSPECTION                => 'Pemeriksaan Perbaikan Baru',
                self::NOTIFICATION_TYPE_NEWWORKORDER                        => 'Pekerjaan Pemeliharaan / Perbaikan Baru'
            ),

            'report_type'  => array(
                self::REPORT_TYPE_SURATPERINGATAN  => 'Surat Peringatan'
            ),

            'suratperingatan_type'  => array(
                self::SURATPERINGATAN_TYPE_1  => 'Surat Peringatan Pertama',
                self::SURATPERINGATAN_TYPE_2  => 'Surat Peringatan Kedua',
                self::SURATPERINGATAN_TYPE_3  => 'Surat Peringatan Ketiga'
            ),

            'placeholder_type'  => array(
                self::PLACEHOLDER_TEMPLATE      => 'Template Laporan',
                self::PLACEHOLDER_COMPONENT     => 'Komponen Laporan',
                self::PLACEHOLDER_REPORT        => 'Laporan'
            ),

            'cashflow_type'  => array(
                self::FINANCE_CASHFLOWTYPE_INVESTING_ACTIVITIES     => 'Investing Activities',
                self::FINANCE_CASHFLOWTYPE_OPERATING_ACTIVITIES     => 'Operating Activities',
                self::FINANCE_CASHFLOWTYPE_FINANCING_ACTIVITIES     => 'Financing Activities',
                self::FINANCE_CASHFLOWTYPE_CASH_BANK                => 'Cash/Bank'
            ),

            'mice_rent_type'    => array(
                self::RENT_TYPE_DAILY       => 'Harian',
                self::RENT_TYPE_HOURLY      => 'Per Jam'
            ),

            'payment_method'    => array(
                self::PAYMENT_METHOD_CASH       => 'Tunai',
                self::PAYMENT_METHOD_CREDITCARD => 'Kartu Kredit',
                self::PAYMENT_METHOD_DEBITCARD  => 'Kartu Debit',
                self::PAYMENT_METHOD_TRANSFER   => 'Transfer'
            )
        );

        return array_merge($registry, $rc->getConstants());
    }
}