const routes = {
    '/404':{
        page    : 'NotFound',
        layout  : null
    },
    '/':{
        page    : 'Home',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        }]
    },
    '/home':{
        page    : 'Home',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        }]
    },
    '/transactions':{
        page    : 'Transactions',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Hareketler',
            href  : '/#/transactions'
        }]
    },
    '/transactions_edit':{
        page    : 'TransactionsT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'İşlemler',
            href  : '/#/transactions'
        },{
            title : 'İşlem Ekle',
            href  : null
        }]
    },
    '/transactionst_edit':{
        page    : 'TransactionsTT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'İşlemler',
            href  : '/#/transactions'
        },{
            title : 'İşlem Ekle / Güncelle',
            href  : null
        }]
    },
    '/accountments':{
        page    : 'Accountments',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Teminatlar',
            href  : '/#/accountments'
        }]
    },
    '/accountments_edit':{
        page    : 'AccountmentsT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Teminatlar',
            href  : '/#/accountments'
        },{
            title : 'Teminatlar Ekle / Düzenle',
            href  : null
        }]
    },
    '/cheque_payrolls':{
        page    : 'Cheque_payrolls',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Çek Bordroları',
            href  : '/#/cheque_payrolls'
        }]
    },
    '/cheque_books':{
        page    : 'Cheque_books',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Çek Defterleri',
            href  : '/#/cheque_books'
        }]
    },
    '/cheque_book_edit':{
        page    : 'Cheque_booksT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Çek Defterleri',
            href  : '/#/cheque_books'
        },{
            title : 'Çek Defterteri Ekle / Düzenle',
            href  : null
        }]
    },
    '/invoices':{
        page    : 'Invoices',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Faturalar',
            href  : '/#/invoices'
        }]
    },
    '/invoices_close':{
        page    : 'InvoicesC',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Faturalar',
            href  : '/#/invoices'
        },{
            title : 'Fatura Kapama',
            href  : null
        }]
    },
    '/invoices_planning':{
        page    : 'InvoicesP',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Tahsilat Planlama',
            href  : '/#/invoices_planning'
        }]
    },
    '/clients':{
        page    : 'Clients',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Cari Kartlar',
            href  : '/#/clients'
        }]
    },
    '/clients_edit':{
        page    : 'ClientsT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Cari Kartlar',
            href  : '/#/clients'
        },{
            title : 'Cari Ekle / Düzenle',
            href  : null
        }]
    },
    '/safes':{
        page    : 'Safes',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Kasa Kartları',
            href  : '/#/safes'
        }]
    },
    '/safes_edit':{
        page    : 'SafesT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Kasa Kartları',
            href  : '/#/safes'
        },{
            title : 'Kasa Ekle / Düzenle',
            href  : null
        }]
    },
    '/sepa':{
        page    : 'Sepa',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Sepa',
            href  : '/#/sepa'
        }]
    },
    '/sepa_edit':{
        page    : 'SepaT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Sepa',
            href  : '/#/sepa'
        },{
            title : 'Sepa Ekle / Düzenle',
            href  : null
        }]
    },
    '/cheques':{
        page    : 'Cheques',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Çek Kartları',
            href  : '/#/cheques'
        }]
    },
    '/cheques_edit':{
        page    : 'ChequesT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Çek Kartları',
            href  : '/#/cheques'
        },{
            title : 'Çek Ekle',
            href  : null
        }]
    },
    '/credits':{
        page    : 'Credits',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Krediler',
            href  : '/#/credits'
        }]
    },
    '/credits_edit':{
        page    : 'CreditsT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Krediler',
            href  : '/#/credits'
        },{
            title : 'Kredi Ekle / Düzenle',
            href  : null
        }]
    },
    '/banks':{
        page    : 'Banks',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Banka Kartları',
            href  : '/#/banks'
        }]
    },
    '/banks_edit':{
        page    : 'BanksT',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Banka Kartları',
            href  : '/#/banks'
        },{
            title : 'Banka Ekle / Düzenle',
            href  : null
        }]
    },
    '/settings':{
        page    : 'Settings',
        layout  : 'AdminLayout',
        bread   : [{
            title : 'Ana Sayfa',
            href  : '/#/home'
        },{
            title : 'Sistem Ayarları',
            href  : '/#/settings'
        }]
    },
    '/login':{
        page:'Login',
        layout:null
    }
};

export default routes;