<?php

class RaspberryPi
{
    private $root;

    public function RaspberryPi(){
    }

    public function hasRoot(){
        if($this->root == null) {
            $this->root = stristr($this->shell('sudo vcgencmd version'), 'Broadcom') !== false;
        }

        return $this->root;
    }

    public function shell($command){
        return trim(shell_exec($command));
    }

    public function getHostName(){
        return gethostname();
    }

    public function getLocalIP(){
        return $_SERVER['SERVER_ADDR'];
    }

    public function getExternalIP(){
        return file_get_contents('http://ipecho.net/plain');
    }

    public function getPHPVersion(){
        return phpversion();
    }

    public function getLoad(){
        return sys_getloadavg();
    }

    public function getUptime(){
        $uptime = explode(' ', file_get_contents('/proc/uptime'));
        return intval($uptime[0]);
    }

    public function getDiskSpace(){
        return disk_free_space('/');
    }

    public function getDiskSize(){
        return disk_total_space('/');
    }

    public function getMemInfo(){
        $data = explode("\n", file_get_contents("/proc/meminfo"));
        $meminfo = array();
        foreach ($data as $line) {
            list($key, $val) = explode(":", $line);
            $meminfo[$key] = substr(trim($val), 0, strlen(trim($val)) - 3);
        }
        return $meminfo;
    }

    public function getPiModel(){
        $revisionRaw = $this->shell('cat /proc/cpuinfo | grep Revision');
        $revision = trim(substr(strstr($revisionRaw, ':'), 1));
        return HumanReadable::getPiVersionFromRevision($revision);
    }

    // start vcgencmd (sudo) commands

    public function vcgenCommand($command){
        if(!$this->hasRoot()) return false;

        return $this->shell('sudo /opt/vc/bin/vcgencmd ' . $command);
    }

    public function getTemperature(){
        return $this->vcgenCommand('measure_temp | cut -c "6-9"');
    }

    public function getCPUClock(){
        $coreClock = explode('=', $this->vcgenCommand('measure_clock arm'));
        return intval($coreClock[1] / 1000000);
    }

    public function getGPUClock(){
        $gpuClock = explode('=', $this->vcgenCommand('measure_clock core'));
        return intval($gpuClock[1] / 1000000);
    }

    public function getVoltage(){
        return $this->vcgenCommand('measure_volts | cut -c "6-9"');
    }

    public function getCPUCores(){
        return $this->shell('nproc');
    }


}
