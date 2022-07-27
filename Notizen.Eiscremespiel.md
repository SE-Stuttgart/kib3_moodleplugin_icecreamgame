# Notizen zum Eiscreme-Plugin:

## Daten für Gruppe 2

statisch ("echte" Daten, 1. und 15. jedes Monats, 2018) 
mithilfe von Excel-Sheet hübsch formatiert und Wetterangaben versprachlicht

## Daten für Gruppe 3

R-Code:
```
maxlist<-c(30,5,10,18,25,-1,8,12,27,19)
wochenendlist<-c(0,1,0,1,0,1,0,1,1,0)
benzinlist<-rep(137,10)
wolkenlist<-c(4,1,8,7,7,5,8,6,3,2)

for (i in seq(1,10,1)) {
    result<-pmax(0,trunc(500+10*(maxlist[i]-10)-0.8*benzinlist[i]^1.3+(benzinlist[i]/100)*(maxlist[i]/20)-2*wolkenlist[i]^1.5+30*wochenendlist[i]*max/10))
    print(paste("Max ",maxlist[i],", Wochenende: ", wochenendlist[i], ", Wolken: ", wolkenlist[i], " -> ", result))
}
```

Diese Versuche als initiale 10 Versuche, weitere Versuche mit Random Inputs. 

## Datenpunkt für finalen Rateversuch/Formel für D

R-Code:
```
max<-23
wolken<-8
wochenende<-1
benzin<-137
pmax(0,trunc(500+10*(max-10)-0.8*benzin^1.3+(benzin/100)*(max/20)-2*wolken^1.5+30*wochenende*max/10))
```
Weitere Versuche mit Random Inputs? 

## Feedback beim finalen Rateversuch:

Feedback sortiert nach Fehler in %. 

Die Bewertung ist nie schlechter als 50%, damit niemand auf die Idee kommt, ein katastrophaler Rateversuch könnte "durchgefallen" bedeuten...


### Fehler 0% - Bewertung 100%

```
Unglaublich, ein Volltreffer - das ist genau die korrekte Lösung!
```

### Fehler bis 10% - Bewertung 80%

```
Sehr gut! Das ist sehr nah an den tatsächlichen Verkäufen und hilft Yvonne sehr weiter! Eine KI wird das kaum besser hinkriegen. 
```

### Fehler bis 30% (parametrisiert: [positiv,negativ]) - Bewertung 50%

``` 
Nicht schlecht, aber schon etwas [über,unter] den tatsächlichen Verkäufen - hier [hätte Yvonne leider Eis wegwerfen müssen,wäre Yvonne einiges an Umsatz entgangen]. 

Bitte seien Sie nicht enttäuscht, wenn Sie keinen perfekten Rateversuch abliefern - es ist für Menschen nicht so leicht, Muster in Daten mit bloßem Auge zu erkennen. In diesem Quiz spielt auch Glück eine Rolle...
```

### Fehler bis 60% (parametrisiert: [positiv,negativ]) - Bewertung 50%

```
Das liegt leider deutlich [über,unter] den Verkäufen - hier [hätte Yvonne leider Eis wegwerfen müssen,wäre Yvonne einiges an Umsatz entgangen]. 

Bitte seien Sie nicht enttäuscht, wenn Sie keinen perfekten Rateversuch abliefern - es ist für Menschen nicht so leicht, Muster in Daten mit bloßem Auge zu erkennen. In diesem Quiz spielt auch Glück eine Rolle...
```

### Fehler bis 100% (parametrisiert: [positiv,negativ]) - Bewertung 50%

```
Das liegt leider sehr deutlich [über,unter] den Verkäufen - hier [hätte Yvonne leider sehr viel Eis wegwerfen müssen,wäre Yvonne sehr viel Umsatz entgangen]. 
```

### Fehler > 100% - Bewertung 50%

```
Das ist leider weit von den tatsächlichen Verkäufen entfernt - haben Sie sich vertippt?
```
