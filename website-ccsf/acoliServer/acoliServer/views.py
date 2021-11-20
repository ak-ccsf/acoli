from django.template import loader

from django.http import HttpResponse, JsonResponse

from . import models

from django.shortcuts import get_object_or_404

from django.core import serializers



def home(request):
    template = loader.get_template('home.html')
    return HttpResponse(template.render({}, request))

def get_city(request, city_id):
    requested_city = get_object_or_404(models.City, pk=city_id)
    print(requested_city.city_name)
    city_as_dictionary = serializers.serialize("json", [requested_city])
    return HttpResponse(city_as_dictionary)

def search(request):
    template = loader.get_template('main.html')
    return HttpResponse(template.render({}, request))






