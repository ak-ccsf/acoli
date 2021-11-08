import React from 'react';
import '../App.css';
import './SearchCityCompareSection.css'
import '../App.css';
import './Button.css'

function SearchCityCompareSection() {
    return (

        <div>
            <img source src="../images/img-7.jpg"/>
            <div className='search-compare'>
                
                <h1>City compare</h1>
                <div className='search-compare-container'>
                    <span> Compare cities in different categories. The most popular comparisons are population, cost of living, average rent, crime rate, tax rates, air quality, religion, local economy, climate, and weather.</span>
                    <div>
                        <h4>Enter 1st Place</h4>
                        <form action='/' method='get'>
                            <input
                                type='search'
                                className='search-compare-field'
                                placeholder='Enter a City'
                            />
                        </form>
                    </div>
                    <div>
                        <h4>Enter 2nd Place</h4>
                        <form action='/' method='get'>
                            <input
                                type='search'
                                className='search-compare-field'
                                placeholder='Enter a City'
                            />
                        </form>
                    </div>
                    <div>
                    <input type="submit" value="Compare Now" className='btn btn--primary'/>
                    </div>
                </div>

            </div>
        </div>
        
    )
}

export default SearchCityCompareSection;