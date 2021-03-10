#!/usr/bin/env python
# -*- coding: utf-8 -*-
import zeep
import datetime

wsdl = 'http://soap2/Smsservice.wsdl'

client = zeep.Client(wsdl=wsdl)
print("Добро пожаловать в soap-smsservice")
phone_number = int(input("Пожалуйста, введите номер телефона которому собираетесь отправлять: "))
text_mess = str(input("Пожалуйста, введите сообщение, которое собиратесь отправить: "))
smsservice = client.get_element('ns0:Smsservice')
obj = smsservice(
    phone = phone_number, 
    text = text_mess,
    date = datetime.date.today(), 
              )
print(client.service.sendSms(Smsservice=obj))


