from django.template import loader

from django.http import HttpResponse

from . import models

from django.shortcuts import get_object_or_404

def home(request):
    template = loader.get_template('home.html')
    return HttpResponse(template.render({}, request))

def get_city(request, city_id):
    requested_city = get_object_or_404(models.City, pk=city_id)
    print(requested_city.city_name)
    template = loader.get_template('home.html')
    return HttpResponse(template.render({}, request))




