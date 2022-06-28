## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 03-08-20

Desarrolo para el grupo Sennova de investigación

## 04-08-20

Importante sobre el uso de seeders.

## 04-08-20

Importante sobre el uso de seeders; el seeder unidadBase carga desde su mismo archivo uno a uno los datos; los demás seeder implementados hasta el dia de hoy cargan de archivos json ubicados en la ruta seeds/json esos son: areas, perfiles, procesos y roles; tener muy en cuenta que en el archivo DatabaseSeeder en donde incluimos las llamadas a los seeder hay que ser cuidadoso con la jerarquia pues las entidades dependen unas de otras, ponerlas secuenciales para no tener problema de que falte algo. Tambien hay que tener en cuenta que en este archivo se implemento una funcion que limpia las tablas y luego las llena con los seeder, asi que se debe ser cuidadoso si se van a correr nuevamente con los datos que se hayan ingresado a mano, si se desea conservar una opcion seria exportar el json desde la base y actualizar los archivos.

## 14-12-20

Actualización listado de colaboradores.
Se añaden a los seeders los datos capturados en las experiencias con los empresarios; se relaciona la seccion a comentariar en caso de querer una base limpia para pruebas en el archivo DatabaseSeeder.

## 17-06-21

Actualización módulos.
Se añaden a los seeders los datos de empleados y los modulos actualizados.

## 15-07-21

Actualización módulo nómina, notas del autor.
Se dimensiona nuevamente el alcance del módulo de nómina tomando las siguientes consideraciones:
1-Se crea una nómina base fuera de los rangos con tipologia 0 y estado 1 para almacenar alli todas las novedades en el momento de ingresarlas; para el 
momento del cierre se valida cuales pertenecen a ella y cumplen con los criterios de la nómina a cerrar.
2-Se piensa en la implementación de una tabla de conceptos para poder consultar rápidamente el tipo
3-Se toma en consideración para las novedades los campos de cantidad unitario y valor, de esta forma analizando la implementación y aplicando el cálculo 
en el momento del ingreso podemos reducir la carga de la tarea de nómina; de igual manera se puede validar actualizaciones en caso de cambios de valor.
4-Deberia tomar en consideración para el cierre de nómina el tipo para sumar o restar, el concepto para el cálculo y voy a dejar en inicio dos botones 
uno para traer los datos estáticos y calcular sobre ellos y otro que calcule en el momento.

## 09-02-22

Creación de nueva rama para implementación multiplataforma.
Inicio de modificaciones en estructuras base para tener en cuenta valor empresa.
Agrego vista de empresa individual.
