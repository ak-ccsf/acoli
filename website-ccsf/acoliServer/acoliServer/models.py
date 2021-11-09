from django.db import models

class City (models.Model):
    cost_of_living = models.FloatField()
    safety_index = models.FloatField()
    climate_index = models.FloatField()
    city_name = models.CharField(max_length=200)
    health_care_index = models.FloatField()
    country = models.CharField(max_length=200)
    pollution_index = models.FloatField()
    property_price_to_income_ratio = models.FloatField()
    purchasing_power_index = models.FloatField()
    region = models.CharField(max_length=200)
    traffic_commute_time_index = models.FloatField()
    
   
    
    








