<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RegInvCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reg = [];

        $reg['The Leden Group Ltd'] = "The Coach House
617 Goffs Lane. Goffs Oak
Hertfordshire
EN7 5HG";

        $reg['Customer c/o Arval UK Ltd - 02644'] = "Arval Business Solutions, Arval Centre
Windmill Hill
Swindon
SN5 6PE";

        $reg['Lex Autolease Limited'] = "Blake House
Hatchford Way
Birmingham
B26 3RZ";

        $reg['Hitachi Capital (UK) Limited'] = "PO Box 3227
Gloucester
GL1 9EQ";


        $reg['LeasePlan UK Ltd (021490)'] = "165 Bath Road
Slough
SL1 4AA";

        $reg['Santander Consumer Finance Contract Hire'] = "PO Box 871
LEEDS
LS1 9RS";

        $reg['ALD Automotive Ltd. 036705'] = "Oakwood Drive
Emersons Green
Bristol
BS16 7LB";

        $reg['ALD Automotive Ltd t/a Ford Lease'] = "Oakwood Drive
Emersons Green
Bristol
BS16 7LB";

        $reg['Alphabet (GB) Ltd'] = "PO Box 1295
Boongate
Peterborough
PE1 9QW";

        $reg['BL Autosource'] = "Couny Gates, Ashton Road
Bristol
BS3 2JH";

        $reg['AA Clark Ltd'] = "Goswell House
Shirley Avenue
Windsor
SL4 5LH";

        $reg['Toomey Leasing Group Ltd'] = "Service House, West Mayne,
Basildon
Essex
SS15 6RW";

        $reg['Agility UK Ltd t/a Agility Fleet'] = "Meridian House, Saxon Business Park
Hanbury Road, Stoke Prior,
Bromsgrove
B60 4AD";

        $reg['BNP Paribas Leasing Solutions Ltd'] = "Nothern Cross, Basing View
Basingstoke, Hampshire
RG21 4HL";

        $reg['ICR LEASING C/O Fevore - 046619'] = "Denbigh House, Denbigh Road
Bletchley, Milton Keynes
MK1 1DF";

        $reg['GKL LEASING'] = "Centenary House, The Bridge Business Centre
Beresford Way, Chesterfield
S41 9FG";

        $reg['Ogilvie Fleet'] = "Thorncliffe Park, Sir Wilfrid Newton House
Newton Chambers Road
Sheffield
S35 2PH";

        foreach ($reg as $name => $address) {
            $reg_company = new \App\RegistrationCompany();
            $reg_company->name = $name;
            $reg_company->address = $address;
            $reg_company->save();
        }

        $inv = [];
        $inv['The Leden Group Ltd'] = "The Coach House
Goffs Lane. Goffs Oak
Hertfordshire
EN7 5HG";

        $inv['Arval UK Limited'] = "Arval Business Solutions, Arval Centre
Windmill Hill
Swindon
SN5 6PE";

        $inv['Lex Autolease Limited'] = "Heathside Park, Heathside Park Road
Cheadle Heath
Stockport
SK3 0RB";

        $inv['Hitachi Capital Vehicle Solutions Limited'] = "Kiln House, 549 Kiln Road
Newbury
Berkshire,
RG14 2ND";


        $inv['LeasePlan UK Ltd (021490)'] = "165 Bath Road
Slough
SL1 4AA";

        $inv['Santander Consumer (UK) PLC t/a'] = "Santander Consumer Contract Hire
One Central Boulevard, Blythe Valley Park
Solihull, West Midlands
B90 8BG";


        $inv['ALD Automotive Ltd.'] = "Oakwood Drive
Emersons Green
Bristol
BS16 7LB";

        $inv['ALD Automotive Ltd t/a Ford Lease'] = "Oakwood Drive
Emersons Green
Bristol
BS16 7LB";

        $inv['Alphabet (GB) Ltd'] = "PO Box 1295
Boongate
Peterborough
PE1 9QW";

        $inv['BL Autosource'] = "County Gates, Ashton Road
Bristol
BS3 2JH";

        $inv['AA Clark Ltd'] = "Goswell House
Shirley Avenue
Windsor
SL4 5LH";

        $inv['Hampshire Trust Bank PLC'] = "55 Bishopsgate
London
EC2N 3AS";

        $inv['Sinclair Finance & Leasing'] = "Hermston Retail Park
Bridgend
CF31 3NB";

        $inv['Aldermore Bank PLC'] = "4th Floor, Block D
Apex Plaza Forbury Road
Reading
RG22 4BB";

        $inv['Toomey Leasing Group Ltd'] = "Service House, West Mayne,
Basildon, Essex,
SS15 6RW";

        $inv['Agility UK Ltd'] = "Meridian House, Saxon Business Park
Hanbury Road, Stoke Prior,
Bromsgrove,
B60 4AD";

        $inv['BNP Paribas Leasing Solutions Ltd'] = "Nothern Cross, Basing View
Basingstoke, Hampshire
RG21 4HL";

        $inv['Lombard North Central PLC'] = "Cyan Building, Adwick Park
Manvers, Wath Upon Dearne
S63 5AD";

        $inv['GKL LEASING'] = "Centenary House, The Bridge Business Centre
Beresford Way, Chesterfield
S41 9FG";

        $inv['Ogilvie Fleet'] = "Thorncliffe Park, Sir Wilfrid Newton House
Newton Chambers Road
Sheffield
S35 2PH";

        foreach ($inv as $name => $address) {
            $reg_company = new \App\InvoiceCompany();
            $reg_company->name = $name;
            $reg_company->address = $address;
            $reg_company->save();
        }

    }
}
