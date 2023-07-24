# Admin panel documentation

The general purpose of this package is to store various templates in database and assigning them to Events so that content based on these templates is automatically generated and/or sent to users.

This package has no Channels or Variables defined, everything should be created in separate packages.

- [Templates-Email](https://github.com/EscolaLMS/Templates-Email/blob/main/ADMIN.md)
- [Templates-Pdf](https://github.com/EscolaLMS/Templates-PDF/blob/main/ADMIN.md)
- [Templates-Sms](https://github.com/EscolaLMS/Templates-SMS/blob/main/ADMIN.md)

The configuration regardless of the channel is similar. 
Go to the *Templates* tab in the Admin Panel and select the tab with the name of the channel for which you want to configure the template.

![Templates](https://github.com/EscolaLMS/Templates/assets/59456825/95471828-1f19-447f-9e96-01b49346a541)

Each template has a name, an event and you can set the template to be the default.
Template processing will be skipped if there is no default template for the event.

![Template form](https://github.com/EscolaLMS/Templates/assets/59456825/4096d705-de0a-4b5d-8e37-bda12ad997cc)

When defining a template, you have variables to use. There are two types of variables global and event-defined. Global variables store general, system-related information, personalized variables store information directly related to the event.
Variables use a convention, with the @ sign before the variable name, to use a variable in a template you need to put the @ sign and the variable name, e.g. @VarSimpleName. 

![Variables](https://github.com/EscolaLMS/Templates/assets/59456825/40c8c82c-ee19-434f-944d-ed2ea94a5fa6)

The variables that are in the *required variables* section are the ones you must use in your template.

![Required variables](https://github.com/EscolaLMS/Templates/assets/59456825/0a5196d6-c2d2-4729-9593-81530601a74e)

Depending on the channel, the configuration of the template content will be different.
