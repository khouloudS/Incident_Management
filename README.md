# REST API

This project has been performed within an interwiew test for Heavytech as a candidate for a summer internship position.

## Audiance

The target audiance of this project is the the Heavytech recruitment team

## High level overview

This deliverable consists of the creation of simple Resful API using PHP.

## The HTTP requests supported are

#### GET

###### GET by ID: 
	
Returns an object using the object ID

API PATH: 
>/Incident_Management/Services/getTickets.php?id={id}
###### GET all:

Returns all objects contained in the database

Request body: Empty

API PATH: 
>/Incident_Management/Services/getTickets.php

#### POST

The post request creates a complete object using the problem_statement (string) and severity (string)

Request body:
```	
{
 'problem_statement': 'string',
 'severity': 'string'
}
```
API PATH: 
>/Incident_Management/Services/addTicket.php

#### PUT

The put request update an object using the problem_statement (string), severity (string) and id (int)

Request body:
```	
{
 'id':'id_value',
 'problem_statement': 'string',
 'severity': 'string'
}
```
API PATH: 
>/Incident_Management/Services/updateTicket.php

#### DELETE

The delete request aims to delete an object using its ID

Request body:

```
{
  'id':'id_value',
}
```
API PATH: 
> /Incident_Management/Services/deleteTicket.php
#### Setup

1. Database creation
```
/Incident_Management/config/ticket.sql
```

  
