# -*- coding:UTF-8 -*-

class Employee:
    empCount = 0

    def __init__(self, name, salary):
        self.name = name
        self.salary = salary
        Employee.empCount += 1

    def displayCount(self):
      print "total employee %d" % Employee.empCount

    def displayEmployee(self):
      print "name:",self.name,",salary:",self.salary


emp1 = Employee("zara",2000)
emp2 = Employee("manni",5000)
emp1.displayEmployee()
emp2.displayEmployee()
print "total employee %d" % Employee.empCount


class Point:
    def __init__(self,x=0,y=0):
       self.x = x
       self.y = y
    def __del__(self):
       class_name = self.__class__.__name__
       print class_name,"销毁"


pt1 = Point()
pt2 = pt1
pt3 = pt1
print id(pt1),id(pt2),id(pt3)
del pt1
del pt2
del pt3


