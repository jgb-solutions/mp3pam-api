import Slider from '@material-ui/lab/Slider';
import { withStyles } from '@material-ui/core/styles';

const CustomSlider = withStyles({
  root: {
    color: '#a4a4a4'
  },
  track: {
    color: '#cd1b54',
    height: 4
  },
  rail: {
    height: 4
  }
})(Slider);

export default CustomSlider;
