# Digital Signage - Cooperativa

La idea del desarrollo es poder mostrar contenido (imagenes, videos, etc) en pantallas de la forma mas sencilla posible, sin conocimientos tecnicos por parte del usuario. Si quisiera mostrar una serie de imagenes por pantalla, los pasos a seguir (a grandes rasgos) serian los siguientes:

1. Subir imagenes al sistema
2. Crear una playlist que contenga esas imagenes
3. Asignar esa playlist a una o mas pantallas

Todos esos pasos se realizan desde el **administrador**, donde se organiza el contenido y se lo asigna a las distintas **pantallas**. 



## Arquitectura general

El back-end otorga las vistas al administrador y a las pantallas, y ademas brinda acceso a la API del sistema para la creacion de playlists, agregado de nuevas pantallas, edicion de su contenido, etc. 

Para la comunicacion en tiempo real entre el administrador y las pantallas (para avisar a una pantalla que su contenido fue modificado por ejemplo) se utiliza comunicacion via web-sockets, evitando un sistema de polling constante y anunciando cambios solo cuando es necesario.

![arquitectura](documentacion/arquitectura.png)

El __udid__ (Unique Device Identifier) es parte del hardware de cada una de las pantallas y permite al servidor reconocer de forma unívoca a cada una (de una de forma que sobrevive a desconexiones, como no lo haria la IP por ejemplo) y asi saber qué vista enviar a cada una. 

La idea de presentar el contenido de cada pantalla como simples _paginas web_ se basa en evitar el desarrollo de aplicaciones completas nativas para distintas plataformas, y hacer que lo maximo que se requiera de forma nativa es una aplicacion que muestra una vista web con el url del servidor y el udid correspondiente. En su version mas sencilla, cualquier dispositivo con un buscador web alcanzaría para presentar contenido.



## Demo

Descargar tanto el 



## Organizacion de las pantallas

Ademas de poder asignar contenido a cada pantalla individualmente, se implemento un sistema de grupos analogo a un sistema de carpetas y archivos, a fin de poder editar el contenido de varias pantallas a la vez (por ej: todas aquellas en el grupo "cajas" muestren la playlist tal). Las pantallas se pueden juntar en grupos, dentro de los cuales pueden haber a su vez mas subgrupos y pantallas. Internamente esto se mantiene en la base de datos usando una implementacion de jerarquias conocida como __nested sets__ (explicada excelentemente por [Myke Hillyer aca](http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/)).

![nested_sets](documentacion/nested_sets.png)





## Uso







## Comunicacion via Web Sockets









## Esquema de la Base de Datos

