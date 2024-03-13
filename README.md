# Stocarea Dotarilor din Universitate – Full Stack App

## Tema principala a poriectului meu este posibilitatea universitatii de a stoca si de a tine o mai buna evidenta a dotarilor din fiecare facultate, implicit din fiecare sala a facultatii.

    LOGIN / REGISTER 
Inceputul implementarii s-a concretizat prin crearea unei pagini de login si de register ce stoca in baza de date informatiile relevante. In dezvoltarea aplicatiei mele, utilizatorii ce isi pot crea conturi pe platforma sunt chiar angajatii facultatilor pe care eu le-am introdus deja in baza de date. Aceasta lista de facultati se poate modifica prin adugare/stergere doar in cazul in care o persoana se conecteaza cu contul de admin, altfel, niciun utilizator nu ar trebui sa aiba posibilitatea sa modifice o parte atat de importanta din aplicatie. Astfel, un nou utilizator isi poate crea un cont nou apasand pe butonul de “Inregistreaza-te” ce il va redirectiona in pagina de mai jos:

![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/cc6cfac1-6b69-493a-83b9-d2943cd57dc0)

Dupa ce contul a fost creat, acesta se poate conecta cu credentialele aferente, mai precis, cu email-ul si cu parola setata chiar de el.

![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/a40693c6-9ead-49c2-909e-3085ede1d70a)

    HOME PAGE
Interfata aplicatiei am incercat sa o creez a fi cat mai prietenoasa si intuitiva. In pagina principala am adugat un adaos destul de interesant, cred eu, bazat pe interogari SQL ce genereaza top 3 sali din baza de date curenta a caror suma a dotarilor este cea mai mare.

![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/ad3e4b26-c6e6-4326-9ab2-01b859c4bfbf)


Mai mult decat atat, un alt adaos este faptul ca, in functie de numele contului cu care esti conectat in aplicatie, pagina iti ureaza un mesaj de bun-venit.

    NAVBAR 
Sau mai popular cunoscut sub numele de “bara de cautari”, a fost implementata pentru a oferi o usurinta in navigarea printre paginile aplicatiei. Butoanele implementate in aceasta sunt “Profil”, “Home”, “Salile Universitatii”, “Lista Dotari”. Pe langa acestea, daca esti conectat cu contul de admin, mai poti observa in bara de cautari si un buton denumit “Statistici” in care am inserat toate interogarile mele, atat simple, cat si cele complexe. 

![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/f6775ba4-daa4-4797-b058-f843d895475b)


    PROFIL
In meniul “Profil” utilizatorul isi poate modifica datele deja existente despre el in baza de date, fiind autocompletate campurile in momentul in care el intra in aceasta pagina, pentru a diminua din timpul petrecut schimbarilor informatiilor din baza de date. Cu toate acestea, meniul de schimbat parola este diferit, parola veche nu este autocompletata deja in campul aferent, trebuie introdusa chiar de utilizator, iar in cazul in care aceasta nu este introdusa corect, aplicatia notifica utilizatorul conform faptului ca nu sunt aceleasi parole, cea introdusa si cea stocata in baza de date.


![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/131508be-0175-4d37-b337-4fe03606b077)

Posibilitatea de stergere a contului este realizata cu ajutorul Javascript, mai precis prin setarea unei alerte pe butonul “Sterge contul”. In momentul in care acesta este apasat, aplicatia primeste in interfata un pop-up in care te roaga sa confirmi actiunea. In momentul in care butonul “OK” este apasat, contul din baza de date se sterge integral.


![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/560caf26-4b76-46d4-a713-2681f00b70df)

    LISTA DOTARI
In aceasta pagina, am afisat toate dotarile existente in universitate, alaturi de campurile aferente, precum `Nume`, `Descriere`, `Data de achitizionare`, `Pret`, si alte 2 butoane prin care poti sa editezi sau sa stergi dotarea din universitate. Modificarea si stergerea au ca efect direct stergerea, respectiv modificarea din toate salile ce contin respectiva dotare.


![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/0a6e2c8e-e1ea-4457-9cd8-7be33f6d9dee)

Butonul “Aduaga Dotare Noua” creaza o noua dotare pe care utilizatorii o pot adauga in salile universitatilor.


![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/5dca28e7-c9ed-4b37-b8de-1e43c8e497af)

Butonul de `Edit`:

![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/f00cceeb-e553-4c93-9a67-f252d215fd64)

Butonul de `Delete`:


  ![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/31608132-cbf4-458e-a6b2-ab9f84dfcee1)

      SALILE UNIVERSITATII 
  In aceasta pagina am adaugat toate salile existente pana in momentul de fata in baza de date. In aceasta saectiune putem observa numele salilor, tipul, capacitatea de care sala dispune in materie de locuri, dotarile care exista in sala respectiva, cladirea din care face parte si departamentul.


  ![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/0c0e8236-4ba8-4787-aef6-3807e2e745be)

      STATISTICI 
  Dupa cum deja am mentionat anterior, aceasta pagina este accesibila doar pentru adminul aplicatiei. Acesta poate vedea butonul in bara de cautari a aplicatiei. O data accesata, putem vedea, delimitat, diferitele interogari simple sau complexe pe care eu le-am formulat pentru aplicatia descrisa. Cu toate acestea, interogarile sunt relevante pentru aplicatia pe care am dezvoltat-o furnizand informatii importante si critice in eventualitatea unei utilizari a aplicatiei.


  ![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/889e4abe-6a9f-4daf-aa11-1916a0c834ca)

    Interogari Complexe
    
- Afiseaza numele si salariul tuturor angajatilor care au salariul mai mare decat salariul median din dep X.


  ![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/112c2ba6-cd22-4824-9605-86ccc32aab92)

- Afiseaza anul de constructie si suprafata totala a cladirilor care au fost constuite in acelasi an cu cladirea X si au o suprafata totala mai mare decat media suprafetelor totale ale cladirilor construite in acelasi an.


    ![image](https://github.com/Mihnea17A1/EMSU/assets/67186861/f99bd213-0a1b-423e-9954-dad6c4818ebc)







