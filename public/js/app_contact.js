Appcontact = Ember.Application.create({
	rootElement: '#emberAppcontactContainer'
});

Appcontact.set('errormessage', '');

//Definiendo Rutas
Appcontact.Router.map(function() {
  this.resource('contacts', function(){
	  this.route('new');
  });
});

var serializer = DS.RESTSerializer.create();

serializer.configure({
    meta: 'meta',
    pagination: 'pagination'
});

//Adaptador
//Applist.Adapter = DS.RESTAdapter.reopen({
//	namespace: MyDbaseUrl,
//	serializer: serializer
//});

// Store (class)
Appcontact.Store = DS.Store.extend({
	revision: 13,
//	adapter: Applist.Adapter.create()
	adapter: DS.FixtureAdapter.extend({
        queryFixtures: function(fixtures, query, type) {
            console.log(query);
            console.log(type);
            return fixtures.filter(function(item) {
                for(prop in query) {
                    if( item[prop] != query[prop]) {
                        return false;
                    }
                }
                return true;
            });
        }
    })
});

// Store (object)
Appcontact.store = Appcontact.Store.create();

//Inicio contactos
Appcontact.Contact = DS.Model.extend({
	email: DS.attr( 'string' ),
	name: DS.attr( 'string' ),
	lastName: DS.attr( 'string' ),
	status: DS.attr( 'number' ),
	activatedOn: DS.attr('string'),
	bouncedOn: DS.attr('string'),
	subscribedOn: DS.attr('string'),
	unsubscribedOn: DS.attr('string'),
	spamOn: DS.attr('string'),
	ipActive: DS.attr('string'),
	ipSubscribed: DS.attr('string'),
	updatedOn: DS.attr('string'),
	createdOn: DS.attr('string'),
	isBounced: DS.attr('boolean'),
	isSubscribed: DS.attr('boolean'),
	isSpam: DS.attr('boolean'),
	isActive: DS.attr('boolean')
	
});

Appcontact.Contact.FIXTURES = [
  { id: 1, email: 'puertorro@hotmail.es', name: 'Fenicio', lastName: 'Cuantindioy', activatedOn: 12345678, bouncedOn: 0, status: true, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 1225455524, ipActive: 13542532, ipSubscribed: 0, isBounced: false, isActive: true, isSpam: true, isSubscribed: true },
  { id: 2, email: 'lachicacandente@hotmail.es', name: 'Lola', lastName: 'Lolita', activatedOn: 12345678, status: true, bouncedOn: 15544512, subscribedOn: 123456, unsubscribedOn: 15171518, spamOn: 0, ipActive: 561151515, ipSubscribed: 14822852, isBounced: true, isActive: true, isSpam: false, isSubscribed: false },
  { id: 3, email: 'superbigman@yahoo.es', name: 'Disney Alberto', lastName: 'Mosquera', activatedOn: 0, status: false,bouncedOn: 0, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 0, ipActive: 0, ipSubscribed: 0, isBounced: false, isActive: false, isSpam: false, isSubscribed: false },
  { id: 5, email: 'yatusabe@live.com', name: 'Maicol Yovany', lastName: 'Icasa', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
  { id: 6, email: 'elcoco@gmail.com', name: 'linux', lastName: 'bin', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
  { id: 7, email: 'labebe@live.com', name: 'mac', lastName: 'var', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
  { id: 8, email: 'ajam@live.com', name: 'Ubuntu', lastName: 'www', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
  { id: 9, email: 'jj@jj.com', name: 'windows', lastName: 'ext', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
  { id: 10, email: 'jojojo@live.com', name: 'fedora', lastName: 'dll', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
  { id: 11, email: 'lol@live.com', name: 'kubuntu', lastName: 'query', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true }
];

//Rutas

Appcontact.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return Appcontact.Contact.find();
	}
});


//Controladores

Appcontact.ContactController = Ember.ObjectController.extend();
Appcontact.ContactsIndexController = Ember.ArrayController.extend({
	
});