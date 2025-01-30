import { DateTime } from 'luxon'
import React from 'react'

const Footer = () => {
    return (
        <footer style={{ textAlign: 'center', padding: '10px 0', backgroundColor: '#f8f9fa', color: '#6c757d', fontSize: 14 }}>
            Version 1.3.1 Release © TechBook by TechYeah <span id="currentYear">{ DateTime.now().toFormat("yyyy")}</span>
        </footer>

    )
}

export default Footer