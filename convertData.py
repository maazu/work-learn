import numpy as np
import pandas as pd
from calendar import monthrange
import json
req_year = input("Enter Year :")
req_year = int(req_year)

def convertOiIntoThousandBarrels(prfPrdOilNetMillSm3,year,month):
    calTotalDayInMonth = monthrange(year, month)
    daysInMonth = calTotalDayInMonth[1]
    barrelPerDay = (prfPrdOilNetMillSm3 * 6.29) * 1000
    thousandBarrelDay = barrelPerDay / daysInMonth ;
    return thousandBarrelDay


def convertGas(prfPrdGasNetBillSm3,year,month):
    calTotalDayInMonth = monthrange(year, month)
    daysInMonth = calTotalDayInMonth[1]
    barrelPerDay = (prfPrdOilNetMillSm3 * 6.29) * 1000
    thousandBarrelDay = barrelPerDay / daysInMonth ;
    return thousandBarrelDay


def monthQuarter(month):
    quarter = 0
    if (month <= 3):
        quarter = 1
        
    elif (month >= 4 and month <= 6):
        quarter = 2
    
    elif (month >= 7 and month <= 9):
        quarter = 3
        
    elif (month >= 10 and month <= 12):
        quarter = 4
    
    return quarter;
  


with pd.option_context('expand_frame_repr', False,'display.max_rows', None):
    url = "https://factpages.npd.no/ReportServer_npdpublic?/FactPages/TableView/field_production_monthly&rs:Command=Render&rc:Toolbar=false&rc:Parameters=f&rs:Format=CSV&Top100=false&IpAddress=5.151.224.1&CultureCode=en"
    fields = ['prfInformationCarrier','prfPrdOilNetMillSm3','prfPrdGasNetBillSm3','prfPrdCondensateNetMillSm3','prfYear','prfMonth']
    
    df = pd.read_csv(url, delimiter=',',  usecols=fields,encoding = 'utf-8')   
    for ind in df.index:
        year = df['prfYear'][ind]
        year = int(year)
        if( req_year == year ):
            file1 = open("convertedData.txt","w") 
            oil = df['prfPrdOilNetMillSm3'][ind]
            gas = df['prfPrdGasNetBillSm3'][ind]
           
            month = df['prfMonth'][ind]
            field_name = df['prfInformationCarrier'][ind]
            condesnation = df['prfPrdCondensateNetMillSm3'][ind] 

            convertedOil = convertOiIntoThousandBarrels(oil,year,month)
            converterdCondensate =  convertOiIntoThousandBarrels(condesnation,year,month)
            monQuarter = monthQuarter(month)
    
            print("({},{},{},{},{},{})".format(field_name,convertedOil,gas,converterdCondensate,year,monQuarter))
