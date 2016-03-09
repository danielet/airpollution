package com.bommason.qiot.qiot;

import android.graphics.Color;

import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.Legend;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.components.YAxis;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;
import com.github.mikephil.charting.interfaces.datasets.ILineDataSet;
import com.github.mikephil.charting.utils.ColorTemplate;

import java.util.ArrayList;

/**
 * Created by BOM2 on 2016-02-09.
 */
public class DrawGraph {
    LineChart m_chart;
    ArrayList<DataArray> m_data_al;

    public DrawGraph(){}

    public DrawGraph(LineChart line_chart, ArrayList<DataArray> al_data){
        this.m_chart = line_chart;
        this.m_data_al = al_data;
    }
    public void AddHeartData(ArrayList<DataArray> al_data){
        this.m_data_al = al_data;
    }

    public void DrawGraphLinePrevious(int index){
        switch(index){
            case 1: // CO graph
                this.m_chart.clear();
                SettingChart();
                for(int j=0; j<m_data_al.size(); j++)
                    addEntry(m_data_al.get(j).time,m_data_al.get(j).co);
                break;
            case 2: // SO2 graph
                this.m_chart.clear();
                SettingChart();
                for(int j=0; j<m_data_al.size(); j++)
                    addEntry(m_data_al.get(j).time,m_data_al.get(j).so2);
                break;
            case 3: // NO2 graph
                this.m_chart.clear();
                SettingChart();
                for(int j=0; j<m_data_al.size(); j++)
                    addEntry(m_data_al.get(j).time,m_data_al.get(j).no2);
                break;
            case 4: // O3 graph
                this.m_chart.clear();
                SettingChart();
                for(int j=0; j<m_data_al.size(); j++)
                    addEntry(m_data_al.get(j).time,m_data_al.get(j).o3);
                break;
            case 5: // PM2.5 graph
                this.m_chart.clear();
                SettingChart();
                for(int j=0; j<m_data_al.size(); j++)
                    addEntry(m_data_al.get(j).time,m_data_al.get(j).pm25);
                break;
            case 6: // PM10 graph
                this.m_chart.clear();
                SettingChart();
                for(int j=0; j<m_data_al.size(); j++)
                    addEntry(m_data_al.get(j).time,m_data_al.get(j).temp);
                break;
            default:
                break;
        }
    }
    public void DrawGraphLineCurrent(){
        addEntry(m_data_al.get(m_data_al.size()-1).h_time,m_data_al.get(m_data_al.size()-1).h_rate);
    }
    public void DrawGraphLineCurrent(int index){
        switch(index){
            case 1: // CO graph
                addEntry(m_data_al.get(m_data_al.size()-1).time,m_data_al.get(m_data_al.size()-1).co);
                break;
            case 2: // SO2 graph
                addEntry(m_data_al.get(m_data_al.size() - 1).time, m_data_al.get(m_data_al.size() - 1).so2);
                break;
            case 3: // NO2 graph
                addEntry(m_data_al.get(m_data_al.size() - 1).time, m_data_al.get(m_data_al.size() - 1).no2);
                break;
            case 4: // O3 graph
                addEntry(m_data_al.get(m_data_al.size() - 1).time, m_data_al.get(m_data_al.size() - 1).o3);
                break;
            case 5: // PM2.5 graph
                addEntry(m_data_al.get(m_data_al.size() - 1).time, m_data_al.get(m_data_al.size() - 1).pm25);
                break;
            case 6: // PM10 graph
                addEntry(m_data_al.get(m_data_al.size() - 1).time, m_data_al.get(m_data_al.size() - 1).temp);
                break;
            default:
                break;
        }
    }

    public void SettingChart(){
        // no description text
        m_chart.setDescription("");
        m_chart.setNoDataTextDescription("You need to provide data for the chart.");
        // enable touch gestures
        m_chart.setTouchEnabled(true);
        // enable scaling and dragging
        m_chart.setDragEnabled(true);
        m_chart.setScaleEnabled(true);
        m_chart.setDrawGridBackground(false);
        // if disabled, scaling can be done on x- and y-axis separately
        m_chart.setPinchZoom(true);

        // set an alternative background color
        m_chart.setBackgroundColor(Color.rgb(220, 220, 220));
        LineData data = new LineData();
        data.setValueTextColor(Color.WHITE);
        // add empty data
        m_chart.setData(data);
        //Typeface tf = Typeface.createFromAsset(getAssets(), "OpenSans-Regular.ttf");
        // get the legend (only possible after setting data)
        Legend l = m_chart.getLegend();
        // modify the legend ...
        // l.setPosition(LegendPosition.LEFT_OF_CHART);
        l.setForm(Legend.LegendForm.LINE);
        //l.setTypeface(tf);
        l.setTextColor(Color.WHITE);
        // X
        XAxis xl = m_chart.getXAxis();
        //xl.setTypeface(tf);
        xl.setTextColor(Color.WHITE);
        xl.setDrawGridLines(false);
        xl.setAvoidFirstLastClipping(true);
        xl.setSpaceBetweenLabels(5);
        xl.setEnabled(true);
        // Y
        YAxis leftAxis = m_chart.getAxisLeft();
        //leftAxis.setTypeface(tf);
        leftAxis.setTextColor(Color.WHITE);
        leftAxis.setAxisMaxValue(2000f);
        leftAxis.setAxisMinValue(0f);
        leftAxis.setStartAtZero(false);
        leftAxis.setDrawGridLines(true);
        YAxis rightAxis = m_chart.getAxisRight();
        rightAxis.setEnabled(false);
    }

    public void addEntry(String str_time, String str_value) {
        LineData data = m_chart.getData();
        if (data != null) {
            ILineDataSet set = data.getDataSetByIndex(0);
            // set.addEntry(...); // can be called as well
            if (set == null) {
                set = createSet();
                data.addDataSet(set);
            }
            // add a new x-value first
            data.addXValue(str_time);
            data.addEntry(new Entry(Float.parseFloat(str_value), set.getEntryCount()), 0);
            // let the chart know it's data has changed
            m_chart.notifyDataSetChanged();
            // limit the number of visible entries
            m_chart.setVisibleXRangeMaximum(120);
            m_chart.setVisibleYRangeMaximum(2000, YAxis.AxisDependency.LEFT);
            // mChart.setVisibleYRange(30, AxisDependency.LEFT);
            // move to the latest entry
            m_chart.moveViewToX(data.getXValCount() - 121);
            // this automatically refreshes the chart (calls invalidate())
            // mChart.moveViewTo(data.getXValCount()-7, 55f,
            // AxisDependency.LEFT);
        }
    }

    public LineDataSet createSet() {
        LineDataSet set = new LineDataSet(null, "Dynamic Data");
        set.setAxisDependency(YAxis.AxisDependency.LEFT);
        set.setColor(ColorTemplate.getHoloBlue());
        set.setCircleColor(Color.WHITE);
        set.setLineWidth(2f);
        set.setCircleRadius(4f);
        set.setFillAlpha(65);
        set.setFillColor(ColorTemplate.getHoloBlue());
        set.setHighLightColor(Color.rgb(244, 117, 117));
        set.setValueTextColor(Color.WHITE);
        set.setValueTextSize(9f);
        set.setDrawValues(false);
        return set;
    }
}
