
def matchstudents2(lista, max, min, solos):
    nuevalista=sorted(lista)
    completos = []
    semicompletos = []
    incompletos = []

    for i in nuevalista:
        if(i==max):
            completos.append(i)
        elif(i>=min):
            semicompletos.append(i)
        else:
            incompletos.append(i)
    
    print(incompletos)
    i = len(incompletos)-1
    while(len(incompletos)>1 and i !=0):
        combinacion = incompletos[0] + incompletos[i]
        if(combinacion<=max):
            
            incompletos.pop(i)
            incompletos.pop(0)
            if (min<=combinacion):
                if (combinacion != max):
                    semicompletos.append(combinacion)
                else:
                    completos.append(combinacion)
            else:
                incompletos.append(combinacion)
            i = len(incompletos)-1
        else:
            i-=1
    semicompletos=sorted(semicompletos)
    incompletos=sorted(incompletos)

    if(len(incompletos)>0):
        for i in range(len(semicompletos)):
            if(len(incompletos)<=0):
                break
            if((incompletos[0]+semicompletos[i])<max):
                semicompletos.append(incompletos[0]+semicompletos[i])
                semicompletos.pop(i)
                incompletos.pop(0)
                semicompletos=sorted(semicompletos)    
                incompletos=sorted(incompletos) 
                i-=1           
            elif((incompletos[0]+semicompletos[i])==max):
                completos.append(incompletos[0]+semicompletos[i])
                semicompletos.pop(i)
                incompletos.pop(0)
                i-=1
        
    print("Completos:" + str(completos))
    print("Semicompletos:" + str(semicompletos))
    print("incompletos:" + str(incompletos))
        
        
def numeroEstudiantes(lista):
    total=0
    for i in lista:
        total+=i
    return total

lista = [1,1,1,1,1,1]
solos = [1,1,1,1,1]

matchstudents2(lista, 6, 3, solos)