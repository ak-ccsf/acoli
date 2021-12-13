#!/usr/bin/env python3

import sqlite3
import unittest


# This class uses unittest to perform several tests on the aqoli.db database
class Testing(unittest.TestCase):

    # test_connection() - Check connection to database
    def test_connection(self):
        try:
            conn = sqlite3.connect('file:../aqoli.db?mode=ro', uri=True)
            connected = True
            conn.close()
        except:
            connected = False
        self.assertTrue(connected)
    

    # test_countries() - Check countries table exists and has > 1 rows
    def test_countries(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        countries = cur.execute('SELECT COUNT(*) FROM countries').fetchone()[0]
        conn.close()
        self.assertTrue(countries > 1)


    # test_country_names() - ensure no country names are null
    def test_country_names(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        no_names = cur.execute('SELECT COUNT(*) FROM countries WHERE country_name IS NULL').fetchone()[0]
        conn.close()
        self.assertEqual(no_names, 0)


    # test_country_urls() - ensure city numbeo urls are unique
    def test_country_urls(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        dup_urls = cur.execute('''SELECT COUNT(*) FROM
                                  (SELECT COUNT(country_url)
                                  FROM countries
                                  GROUP BY country_url
                                  HAVING COUNT(country_url) > 1)
                               ''').fetchone()[0]
        conn.close()
        self.assertEqual(dup_urls, 0)


    # test_cities() - Check cities table exists and has > 1 rows
    def test_cities(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        cities = cur.execute('SELECT COUNT(*) FROM cities').fetchone()[0]
        conn.close()
        self.assertTrue(cities > 1)


    # test_city_names() - ensure no city names are null
    def test_city_names(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        no_names = cur.execute('SELECT COUNT(*) FROM cities WHERE city_name IS NULL').fetchone()[0]
        conn.close()
        self.assertEqual(no_names, 0)


    # test_numbeo_urls() - ensure city numbeo urls are unique
    def test_numbeo_urls(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        dup_urls = cur.execute('''SELECT COUNT(*) FROM
                                  (SELECT COUNT(city_url)
                                  FROM cities
                                  GROUP BY city_url
                                  HAVING COUNT(city_url) > 1)
                               ''').fetchone()[0]
        conn.close()
        self.assertEqual(dup_urls, 0)
    

    # test_qol() - Check quality_of_life table exists and has > 1 rows
    def test_qol(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        qol = cur.execute('SELECT COUNT(*) FROM quality_of_life').fetchone()[0]
        conn.close()
        self.assertTrue(qol > 1)


    # test_qol_ids() - ensure quality_of_life table contains no duplicate city ids
    def test_qol_ids(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        dup_ids = cur.execute('''SELECT COUNT(*) FROM
                                 (SELECT COUNT(city_id)
                                 FROM quality_of_life
                                 GROUP BY city_id
                                 HAVING COUNT(city_id) > 1)
                              ''').fetchone()[0]
        conn.close()
        self.assertEqual(dup_ids, 0)


    # test_climate() - Check climate table exists and has > 1 rows
    def test_climate(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        climate = cur.execute('SELECT COUNT(*) FROM climate').fetchone()[0]
        conn.close()
        self.assertTrue(climate > 1)


    # test_climate_ids() - ensure climate table contains no duplicate city ids
    def test_climate_ids(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        dup_ids = cur.execute('''SELECT COUNT(*) FROM
                                 (SELECT COUNT(city_id)
                                 FROM climate
                                 GROUP BY city_id
                                 HAVING COUNT(city_id) > 1)
                              ''').fetchone()[0]
        conn.close()
        self.assertEqual(dup_ids, 0)


    # test_image_urls() - Check image_urls table exists and has > 1 rows
    def test_image_urls(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        climate = cur.execute('SELECT COUNT(*) FROM image_urls').fetchone()[0]
        conn.close()
        self.assertTrue(climate > 1)


    # test_image_ids() - ensure image_urls table contains no duplicate city ids
    def test_image_ids(self):
        conn = sqlite3.connect('../aqoli.db')
        cur = conn.cursor()
        dup_ids = cur.execute('''SELECT COUNT(*) FROM
                                 (SELECT COUNT(city_id)
                                 FROM image_urls
                                 GROUP BY city_id
                                 HAVING COUNT(city_id) > 1)
                              ''').fetchone()[0]
        conn.close()
        self.assertEqual(dup_ids, 0)


if __name__ == '__main__':
    unittest.main()
