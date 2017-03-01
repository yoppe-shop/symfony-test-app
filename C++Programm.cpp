#include <iostream>
#include <memory>

using namespace std;

class Auto
{
    public:
    Auto();
    void echoInfo();
    int i;    
};

Auto :: Auto()
{
   i = 3;
}

void Auto :: echoInfo()
{
    cout << 3 << endl;    
}

class Volkswagen : public Auto
{
    public:
    Volkswagen();
    void echoInfo();
};

Volkswagen :: Volkswagen()
{
    i = 5;
}

void Volkswagen :: echoInfo()
{
    cout << 5 << endl;
}


void autoInfo(unique_ptr<Auto> &meinAuto)
{
   meinAuto->echoInfo();    
}


int main()
{
   unique_ptr<Auto> meinAuto(new Volkswagen());
   autoInfo(meinAuto);
   return 0;
}

