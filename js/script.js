

    function _getSecondsDisplay(seconds){
      return (seconds < 10) ? "0" + seconds : seconds; 
    }

    function getTimeDisplay(seconds){
      var text = "";  
      text = Math.floor(seconds / 60) + ":" + _getSecondsDisplay(seconds % 60);
      return text;
    }