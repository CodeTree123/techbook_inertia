function Shade(props) {
    const {title} = props;
    return (
        <div
            className="shade my-3"
            style={{
                padding: '10px 20px',
                backgroundColor: 'rgba(175, 225, 175, 0.5)',
                color: 'black',
                textAlign: 'center',
                borderRadius: '12px',
                fontSize: '18px',
                display: 'inline-block',
            }}
        >
            <h5 className="cardTitle">{title}</h5>
        </div>
    );
}

export default Shade;
