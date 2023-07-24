# Admin panel documentation

The general purpose of this package is to store various templates in database and assigning them to Events so that content based on these templates is automatically generated and/or sent to users.

This package has no Channels or Variables defined, everything should be created in separate packages.

- [Templates-Email](https://github.com/EscolaLMS/Templates-Email/blob/main/ADMIN.md)
- [Templates-Pdf](https://github.com/EscolaLMS/Templates-PDF/blob/main/ADMIN.md)
- [Templates-Sms](https://github.com/EscolaLMS/Templates-SMS/blob/main/ADMIN.md)

The configuration regardless of the channel is similar. 
Go to the *Templates* tab in the Admin Panel and select the tab with the name of the channel for which you want to configure the template.

[img]

Each template has a name and an event, and you can set the template to be the default.
Template processing will be skipped if there is no default template for the event.

[img]

When defining a template, you have variables to use. There are two types of variables global and event-defined. Global variables store general, system-related information, personalized variables store information directly related to the event.
Variables use a convention, with the @ sign before the variable name, to use a variable in a template you need to put the @ sign and the variable name, e.g. @VarSimpleName. 

[img]

The variables that are in the *required variables* section are the ones you must use in your template.

[img]

Depending on the channel, the configuration of the template content will be different.
