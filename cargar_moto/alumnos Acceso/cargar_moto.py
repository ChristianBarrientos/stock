# -*- coding: utf-8 -*-
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
import time
import pymysql
from selenium.webdriver.support.ui import Select
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import NoSuchElementException
import xlwt
import xlrd

#browser = webdriver.Chrome("chromedriver.exe")
#aulas = []
#alumnos = []
##Link para las estadisticas 
##http://campus.humavirtual-unca.edu.ar/administracion/redirect.cgi?wid_cursoActual=442&goto=admin_estadisticas.cgi

def my_range(start, end, step):
    while start <= end:
        yield start
        start += step

def login():
	browser.get("http://localhost:8080/stock/stock/") 
	input_user = browser.find_element_by_id("usuario")
	input_pass = browser.find_element_by_id("pass")
	input_user.send_keys("stock_moto")
	input_pass.send_keys("mOtomAtch7102.")
	login_attempt = browser.find_element_by_xpath("/html/body/div/div/div/div[2]/form/div[3]/button")
	login_attempt.submit()


def obtener_aulas():
	#secs = 4
	url = 'http://campus.humavirtual-unca.edu.ar/index.cgi?id_curso=80'
	browser.get(url)
	#browser.implicitly_wait(secs)
	browser.get(url)
	element = browser.find_element_by_id("selector")
	all_options = element.find_elements_by_tag_name("option")
	
	for option in all_options:
		
		valor_select = option.get_attribute("value")
		url = url[:57] + valor_select
		aulas.append([url,valor_select,option.text])

def insertar_aulas(aula, id_aula):
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	cur.execute(""" INSERT INTO cm_aulas(id_aula, nombre) VALUES (%s,%s) """,(id_aula,aula))
	conn.commit()
	cur.close()
	conn.close()

def insertar_contactos(nombre, aula, id_aula, usuario, comision):
	
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	cur.execute(""" INSERT INTO cm_alumno(id_alumno, nombre_apellido,usuario,comision,id_aula) VALUES (0,%s,%s,%s,%s) """,(nombre,usuario,comision,id_aula))
	conn.commit()
	cur.close()
	conn.close()

def insertar_contactos_usuario(usuario,id_alumno):
	
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	cur.execute(""" UPDATE cm_alumno SET usuario = %s WHERE  id_alumno = %s """,(usuario,id_alumno))
	conn.commit()
	cur.close()
	conn.close()

def volar_todo(usuario):
	
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	##Realizar consulta
	rows_count = cur.execute("SELECT * FROM `cm_alumno` WHERE usuario = %s",(usuario))
	#rows_count = cursor.execute(query_sql)
	if rows_count > 0:
	    return False
	else:
		return True
	cur.close()
	conn.close()	
def obtener_contactos():
	url = 'http://campus.humavirtual-unca.edu.ar/contactos.cgi?id_curso=80'
	i = 0
	
	iterado = 0
	
	
	aulas_c4 = [['Analisis Institucional 4C-A', 'A'],['Analisis Institucional 4C-B', 'B'],['Analisis Institucional 4C-C', 'C'],
				['Financiamiento de la Edu. 4C-A','A'],['Financiamiento de la Edu. 4C-B','B'],['Financiamiento de la Edu. 4C-C','C'],
				['Política Educativa 4C-A','A'],['Política Educativa 4C-B','B'],['Política Educativa 4C-C','C'],
				['Teo. Adm. y Org. Edu. 4C-A','A'],['Teo. Adm. y Org. Edu. 4C-B','B'],['Teo. Adm. y Org. Edu. 4C-C','C']]	
	
	for ok in aulas:
		url = url[:61] + ok[1]
		
		try:
				
				
			if iterado < len(aulas_c4):
				
				if aulas[i][2] in aulas_c4[iterado][0]:
					browser.get(url)
					
					
				else:
					
					i = i + 1
					continue
			else:
				break
		#Alumnos  	/html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[3]/td/div[2]/table/tbody
		
		
			element_alumnos = browser.find_element_by_xpath("/html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[3]/td/div[2]/table/tbody")
			all_options_alumnos = element_alumnos.find_elements_by_class_name("col_apellido")
			all_options_alumnos_usuario = element_alumnos.find_elements_by_class_name("col_selector")
			
			
			#all_options_alumnos_usuario = element_alumnos.find_elements_by_tag_name("inputbox")
			#checs_usuario_alumno = browser.find_elements_by_tag_name("inputbox")
			#input.get_attribute('value')
		#Tutores                                         /html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[5]/td/div[2]/table/tbody
		#element_tutores = browser.find_element_by_xpath("/html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[5]/td/div[2]/table/tbody")
		#all_options_tutores = element.find_elements_by_class_name("col_apellido")
		
		#Administrador del campus                        /html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[7]/td/div[2]/table/tbody
		#element_tutores = browser.find_element_by_xpath("/html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[7]/td/div[2]/table/tbody")
		#all_options_tutores = element.find_elements_by_class_name("col_apellido")
		
		#Coordinadinadores
		#element_coordiandores = browser.find_element_by_xpath("/html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[7]/td/div[2]/table/tbody")
		#all_options_coordiandores = element.find_elements_by_class_name("col_apellido")
		#Sector Pagos                                        /html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[9]/td/div[2]/table/tbody
		#element_sectorPagos = browser.find_element_by_xpath("/html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[7]/td/div[2]/table/tbody")
		#all_options_sectorPagos = element.find_elements_by_class_name("col_apellido")
		#all_options2 = element.find_elements_by_tag_name("td")
		#all_options3 = all_options.find_elements_by_tag_name("td")
							 
		#all_options = element.find_elements_by_tag_name("option")
		#find_element_by_class_name
			
			#print(aulas[i][1] +'\n')
			#print(aulas[i][2] +'\n')
			#print(aulas_c4[iterado][0] + '\n' + aulas_c4[iterado][1])
			insertar_aulas(aulas[i][2],aulas[i][1])
			#for option in all_options_alumnos:
				
				#print(option.text)
				
				#insertar_contactos(option.text,aulas[i][2],aulas[i][1])
			
			
			
			print(aulas_c4[iterado][0] + '  ' + aulas_c4[iterado][1])
			print('\n')
			time.sleep(2)
			repetidos = []
			for n in range(len(all_options_alumnos_usuario)):
				all_options_alumnos_usuario_2 = all_options_alumnos_usuario[n].find_element_by_tag_name("input")
					
				#print (all_options_alumnos_usuario_2.get_attribute("id"), all_options_alumnos[n].text())
				nombre_alumno = all_options_alumnos[n].text
				usuario_alumno = all_options_alumnos_usuario_2.get_attribute("id").replace("Check-",'') #usuario
				aula_alumno = aulas_c4[iterado][0]
				comision_alumno = aulas_c4[iterado][1]

				print(nombre_alumno + ' ' + usuario_alumno)
				
				if volar_todo(usuario_alumno):
					insertar_contactos(nombre_alumno,aula_alumno,aulas[i][1], usuario_alumno, comision_alumno)

				#if usuario_alumno in repetidos:
				#	pass
				#else:
				#	print(usuario_alumno)
				#	insertar_contactos(nombre_alumno,aula_alumno,aulas[i][1], usuario_alumno, comision_alumno)

				repetidos.append(usuario_alumno)

		except NoSuchElementException:
			print("Oops!! Sin Permisos!!")
		finally:
			pass
		i = i +1
		iterado = iterado + 1

def obtener_usuarios():
	url = 'http://campus.humavirtual-unca.edu.ar/contactos.cgi?id_curso=426'
	i = 0
	
	browser.get(url)

	element_alumnos = browser.find_element_by_xpath("/html/body/div[3]/div[2]/div[3]/div/form/table/tbody/tr[3]/td/div[2]/table/tbody")
	all_options_alumnos = element_alumnos.find_elements_by_class_name("col_apellido")
	all_options_alumnos_usuario = element_alumnos.find_elements_by_class_name("col_selector")
			
	for n in range(len(all_options_alumnos_usuario)):
		all_options_alumnos_usuario_2 = all_options_alumnos_usuario[n].find_element_by_tag_name("input")
		#all_options_alumnos_usuario_2 = all_options_alumnos_usuario[n].find_element_by_id("input")			
		#print (all_options_alumnos_usuario_2.get_attribute("id"), all_options_alumnos[n].text())
		print(all_options_alumnos_usuario_2.get_attribute("id"))
	
		#print(all_options_alumnos_usuario[n])


def insertar_ultimo_acceso(id_alumno,id_aula,ultimo_acc):
	
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	cur.execute(""" INSERT INTO cm_aula_alumno(id_aula_alumno, id_alumno,id_aula,ultimo_acceso) VALUES (0,%s,%s,%s) """,(id_alumno,id_aula,ultimo_acc))
	conn.commit()
	cur.close()
	conn.close()

def obtener_id_alumno(al):
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	##Realizar consulta
	cur.execute("SELECT * FROM cm_alumno WHERE nombre_apellido = %s",(al))
	for row in cur:
		return row[0]
	cur.close()
	conn.close()

def obtener_acceso():
	#URL Reportes totales
	url = 'http://campus.humavirtual-unca.edu.ar/administracion/redirect.cgi?wid_cursoActual='
	url_2 = '&goto=admin_estadisticas.cgi'
	#URL POR x USUARIO
	url_3 = 'http://campus.humavirtual-unca.edu.ar/administracion/admin_estadisticas.cgi?wAccion=accesos&wid_cursoActual='
	
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	##Realizar consulta
	cur.execute("SELECT * FROM cm_aulas")
	
	aulas = []

	#XPATH 
	#/html/body/div[2]/div[4]/div/table[2]/tbody/tr/td/table/tbody/tr[4]/td[1]/font/a
	#/html/body/div[2]/div[4]/div/table[2]/tbody/tr/td/table/tbody/tr[4]/td[2]/div/font
	#XPATH bandera
	bandera = 4
	#XPATH para el Nombre
	xnomnbre = '/html/body/div[2]/div[4]/div/table[2]/tbody/tr/td/table/tbody/tr['
	xnomnbre_2 = ']/td[1]/font/a'
	#XPATH para la Fecha de Acceso
	xfecha = '/html/body/div[2]/div[4]/div/table[2]/tbody/tr/td/table/tbody/tr['
	xfecha_2 = ']/td[2]/div/font'
	for row in cur:
		cantidad = 0
		id_aula = row[0]
		nombre_aula = row[1]

		url_cpt = url_3 + str(id_aula) + url_2
		browser.get(url_cpt)

		print(nombre_aula)
		print('Nombre Alumno' + '     ' + 'Fecha Ultimo Acceso')
		for x in my_range(4, 10000, 2):
			xnombre_cmp = ''
			xfecha_cmp = ''
			xnombre_cmp = xnomnbre + str(x) + xnomnbre_2
			xfecha_cmp = xfecha + str(x) + xfecha_2

	    	
			try:
				alumno_nom = browser.find_element_by_xpath(xnombre_cmp)
				acceso_fech = browser.find_element_by_xpath(xfecha_cmp)
				#alu = alumno_nom.find_element_by_tag_name("a")
				#aulas.append([id_aula,nombre_aula,alumno_nom.text(),acceso_fech.text()])
				#alumno_nom.get_attribute("size")
				#alumno_nom = str(alumno_nom.text())
				#acceso_fech = str(acceso_fech.text())
				
				#xnombre_cmp = ''
				#xfecha_cmp = ''
				id_usuario = obtener_id_alumno(alumno_nom.text)
				aulas.append([id_aula,nombre_aula,alumno_nom.text,acceso_fech.text])
				#insertar_ultimo_acceso()
				print(str(id_usuario) + ' ' + str(id_aula) + '     ' + acceso_fech.text)
				insertar_ultimo_acceso(id_usuario,id_aula,acceso_fech.text)
				cantidad = cantidad + 1
			except NoSuchElementException:
				print("Fin")
				print('\n' + 'total alumnos: ' + str(cantidad))
				time.sleep(3)
				break
			
			
		 
	cur.close()
	conn.close()

	#for datos in aulas:

	#	print(datos[2],[3])
		
def obtener_datos_acceso(id_aula):
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	##Realizar consulta
	cur.execute("""SELECT cm_alumno.nombre_apellido,cm_aula_alumno.ultimo_acceso,cm_aulas.nombre FROM cm_aulas, cm_alumno , cm_aula_alumno WHERE cm_alumno.id_alumno = cm_aula_alumno.id_alumno AND cm_aula_alumno.id_aula = cm_aulas.id_aula AND  cm_aulas.id_aula = %s""",(id_aula))
	return cur
	cur.close()
	conn.close()

def obtener_alumnos_por_comision(letra_comision):
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	##Realizar consulta
	cur.execute("SELECT * FROM `cm_alumno` WHERE comision = %s",(letra_comision))
	return cur
	cur.close()
	conn.close()

def obtener_ultimo_acceso(id_alumno, id_aula):
	conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='lista_alumnos_acceso')
	cur = conn.cursor()
	##Realizar consulta
	cur.execute("SELECT ultimo_acceso FROM `cm_aula_alumno` WHERE id_alumno = %s AND id_aula = %s",(id_alumno,id_aula))
	for x in cur:
		return (x[0])
	cur.close()
	conn.close()

def generar_exel():


#----------------------------------------------------------------------
	aulas_c4 = [['Analisis Institucional 4C-A', 'A'],['Analisis Institucional 4C-B', 'B'],['Analisis Institucional 4C-C', 'C'],
				['Financiamiento de la Edu. 4C-A','A'],['Financiamiento de la Edu. 4C-B','B'],['Financiamiento de la Edu. 4C-C','C'],
				['Política Educativa 4C-A','A'],['Política Educativa 4C-B','B'],['Política Educativa 4C-C','C'],
				['Teo. Adm. y Org. Edu. 4C-A','A'],['Teo. Adm. y Org. Edu. 4C-B','B'],['Teo. Adm. y Org. Edu. 4C-C','C']]	
	#A= 0,3,6,9
	#B= 1,4,7,10
	#C= 2,5,8,11
	comisiones = ['A','B','C']

	for x, comi in enumerate(comisiones):
		
		libro = xlwt.Workbook()
		
		

		libro1 = libro.add_sheet('Listado Acceso Comision'+comi)

		cols = ['Analisis Institucional 4C-','Financiamiento de la Edu. 4C-','Política Educativa 4C-','Teo. Adm. y Org. Edu. 4C-']
		txt = "Fila %s, Columna %s"
		titulo = ['Nombre y Apellido', cols[0],cols[1],cols[2],cols[3]]
		salto = 0

		
		param2 = '4ta Cohorte Comision'

		#materia = cols + param
		id_aulas = [[436,433,434,435],[443,437,439,441],[444,438,440,442]]

		lista_alumnos = obtener_alumnos_por_comision(comi)

		basura = []

		dios = 0
		row = libro1.row(0)

		for index, tit in enumerate(titulo):
			if index == 0:
				row.write(index, tit)

			else:
				row.write(index, tit + comi)
			

		#print(dupli)	
		
		dios = 0

		for i, datos in enumerate(lista_alumnos):
			
			dios = dios + 1
			row = libro1.row(dios)

			
			
			#index2 = 0
			for index, tit in enumerate(titulo):
				
				

				
					
				if index > 0:

					aux = index - 1
						#print(id_aulas[x][aux])
						#if (datos[0] in basura) and (id_aulas[x][aux] in basura):
						#	print("haha")
							
						#else:
						#print(datos[0])
					ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][aux])
					row.write(index, ultimo_momento)
						#	basura.append(datos[0])
						#	basura.append(id_aulas[x][aux])

				else:
					aux2 = index + 1
					row.write(index, datos[aux2])
					#row.write(index, tit + comi)

			salto = 6			
				
		
		param2 = param2 + ' ' + comi + """.xls"""
		libro.save(param2)
		#if (salto < 6) or (salto == 0):
					#row.write(salto, tit + param)
					
				#	if salto == 0:
				#		row = libro1.row(i)
				#		row.write(index, tit)
				#	else:
				#		row.write(index, tit + comi)
				#	salto = salto + 1
					
					#ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][aux])
					#row.write(index, ultimo_momento)
				#else:
		#lista_acceso.append([''])
		#for y,materia in enumerate(cols):
			
		#	lista_acceso.append(obtener_datos_acceso(id_aulas[x][y]))
		#lista_acceso_1 = 
		#lista_acceso_2 = obtener_datos_acceso(id_aulas[0][1])
		#lista_acceso_3 = obtener_datos_acceso(id_aulas[0][2])
		#lista_acceso_4 = obtener_datos_acceso(id_aulas[0][3])
			
		
		#for datos in lista_acceso:
		#	cantidad = cantidad+1
		#solouno = 0
		#for x in lista_acceso:

		#	for y in x:
		#		for z in y:
		#			if solouno == 0:
		#				print(y)
		#				solouno = 1
		#		solouno = 0
		#		print('\n')
		#	print('Materia')
		#print("comision")
				
			
		
		






def generar_exel_2():


#----------------------------------------------------------------------
	aulas_c4 = [['Analisis Institucional 4C-A', 'A'],['Analisis Institucional 4C-B', 'B'],['Analisis Institucional 4C-C', 'C'],
				['Financiamiento de la Edu. 4C-A','A'],['Financiamiento de la Edu. 4C-B','B'],['Financiamiento de la Edu. 4C-C','C'],
				['Política Educativa 4C-A','A'],['Política Educativa 4C-B','B'],['Política Educativa 4C-C','C'],
				['Teo. Adm. y Org. Edu. 4C-A','A'],['Teo. Adm. y Org. Edu. 4C-B','B'],['Teo. Adm. y Org. Edu. 4C-C','C']]	
	#A= 0,3,6,9
	#B= 1,4,7,10
	#C= 2,5,8,11
	
	comisiones = ['A','B','C']

	for x, comi in enumerate(comisiones):
		
		libro = xlwt.Workbook()
		
		

		libro1 = libro.add_sheet('Sin Acceso Nunca Comision' + comi)

		cols = ['Analisis Institucional 4C-','Financiamiento de la Edu. 4C-','Política Educativa 4C-','Teo. Adm. y Org. Edu. 4C-']
		txt = "Fila %s, Columna %s"
		titulo = ['Nombre y Apellido', cols[0],cols[1],cols[2],cols[3]]
		salto = 0

		
		param2 = '4ta Cohorte Comision Sin Acceso Nunca'

		#materia = cols + param
		id_aulas = [[436,433,434,435],[443,437,439,441],[444,438,440,442]]

		lista_alumnos = obtener_alumnos_por_comision(comi)

		basura = []

		dios = 0
		row = libro1.row(0)

		for index, tit in enumerate(titulo):
			if index == 0:
				row.write(index, tit)

			else:
				row.write(index, tit + comi)	
		
		dios = 0
		control = False
		nombre = ''
		alumnos_sin_accesp = []
		#aulas.append([url,valor_select,option.text])
		for i, datos in enumerate(lista_alumnos):
			#row = libro1.row(i)
			for index, tit in enumerate(titulo):
				
				if index > 0:
					aux = index - 1
					ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][aux])

					#ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][index])
					if '-' in str(ultimo_momento):	
						control = True	
						mat_1 = ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][0])	
						mat_2 = ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][1])	
						mat_3 = ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][2])	
						mat_4 = ultimo_momento = obtener_ultimo_acceso(datos[0],id_aulas[x][3])
						nombre = datos[1]
						break
							#row.write(0, datos[1])
					else:
						control = False
				else:
					continue

			if control:
				alumnos_sin_accesp.append([nombre,mat_1,mat_2,mat_3,mat_4])
			
		dios = 0
		for i,datoss in enumerate(alumnos_sin_accesp):
			dios = dios + 1 
			row = libro1.row(dios)
			
			for z,info in enumerate(datoss):
				#print(info)
				#print(z)
				row.write(z, info)

		
		param2 = param2 + ' ' + comi + """.xls"""
		libro.save(param2)
	
def leer_excel_artiiculo():
	i = 1
	book = xlrd.open_workbook("LeerPy.xlsx")
	sh = book.sheet_by_index(0)
	
	 
	for rx in range(sh.nrows):
		#print(sh.cell_value(rowx=i, colx=0))

		valor = [sh.cell_value(rowx=i, colx=0)]
		articulos.append(valor[0])
		 
		#i = i + 1
		#if i == 10:
		#	break

def leer_excel_costo():
	i = 1
	book = xlrd.open_workbook("LeerPy.xlsx")
	sh = book.sheet_by_index(0)
	
	 
	for rx in range(sh.nrows):
		#print(sh.cell_value(rowx=i, colx=0))
		valor = [sh.cell_value(rowx=i, colx=1)]
		costo.append(valor[0])
		 
		#i = i + 1
		#if i == 10:
		#	break

def leer_excel_porciento():
	i = 1
	book = xlrd.open_workbook("LeerPy.xlsx")
	sh = book.sheet_by_index(0)
	
	 
	for rx in range(sh.nrows):
		#print(sh.cell_value(rowx=i, colx=0))
		valor = [sh.cell_value(rowx=i, colx=2)]
		#print (valor[0])
		porciento.append(valor[0])
		#print (porciento[i])
		#i = i + 1
		#if i == 10:
		#	break

def leer_excel_stock():
	i = 1
	book = xlrd.open_workbook("LeerPy.xlsx")
	sh = book.sheet_by_index(0)
	
	 
	for rx in range(sh.nrows):
		#print(sh.cell_value(rowx=i, colx=0))
		valor = [sh.cell_value(rowx=i, colx=3)]
		#print (valor[0])
		stock.append(valor[0])
		 
		#i = i + 1
		#if i == 10:
		#	break

##browser = webdriver.Chrome("chromedriver.exe")
##aulas = []

def cargar_motostock(index):
	browser.get("http://localhost:8080/stock/stock/index.php?action=Articulo::cargar_deposito")

	#for x in range(0,len(articulos)):
    	 
		
	input_art_general = browser.find_element_by_id("select_art_general")
	input_art_general.send_keys("MotoMatch")

	inpurt_art_marca = browser.find_element_by_id("select_art_marca")
	inpurt_art_marca.send_keys("MotoMatch")	

	input_art_tipo = browser.find_element_by_id("select_art_tipo")
	input_art_tipo.send_keys(articulos[index])

	input_art_precio = browser.find_element_by_id("art_precio_base")
	input_art_precio.send_keys(str(costo[index]))	

	input_art_ganancia = browser.find_element_by_id("art_ganancia")
	input_art_ganancia.send_keys(str(porciento[index]))

	input_art_stock = browser.find_element_by_id("art_local_cantidad_")
	input_art_stock.send_keys(str(stock[index]))

	#login_attempt = browser.find_element_by_xpath("//*[@id='art_carga_btn']")
	#login_attempt.submit()
	btn_cargar_art = browser.find_element_by_xpath("//*[@id='art_carga_btn']")
	btn_cargar_art.submit()
	print("OK" + str(articulos[x]) + ' ' + str(porciento[x]) + ' ' + str(stock[x]))

		


articulos = []
costo = []
porciento = []
stock = []
leer_excel_artiiculo()
leer_excel_costo()
leer_excel_porciento()
leer_excel_stock()

#print(articulos)
#for x in range(0,len(porciento)):
#	print(articulos[x])
browser = webdriver.Chrome("chromedriver.exe")
login()
for x in range(0,len(articulos)):
	print(x)
	cargar_motostock(x)
#login()
#obtener_aulas()
#obtener_usuarios()
#obtener_contactos()
#obtener_acceso()
#generar_exel()
#generar_exel_2()



#TR-ID
#uslA-tr0 --> Alumnos
#uslP-tr0 --> Tutores
#uslX-tr0 --> Coordinadores
#uslD-tr0 --> Sector Pagos
#	TD-CLASS
#	col_apellido celda

#browser.find_element(By.CSS_SELECTOR, 'tr#slA-tr0:nth-child(2)')
#url = 'http://campus.humavirtual-unca.edu.ar/contactos.cgi?id_curso=80'


#/html/body/div[2]/div[4]/div/table[2]/tbody/tr/td/table/tbody/tr[4]/td[1]/font/a

#/html/body/div[2]/div[4]/div/table[2]/tbody/tr/td/table/tbody/tr[4]/td[1]/font/a