package com.example.stream.ui.home;

import android.content.Context;
import android.provider.Settings;

import com.example.stream.data.net.ApiClient;

import java.util.HashMap;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class WatchLaterHelper {
    private final Context context;

    public WatchLaterHelper(Context context) {
        this.context = context.getApplicationContext();
    }

    private String deviceId() {
        return Settings.Secure.getString(context.getContentResolver(), Settings.Secure.ANDROID_ID);
    }

    public void add(int contentId) {
        ApiClient.getInstance().addWatchLater(deviceId(), contentId).enqueue(new Callback<HashMap<String, Object>>() {
            @Override public void onResponse(Call<HashMap<String, Object>> call, Response<HashMap<String, Object>> response) {}
            @Override public void onFailure(Call<HashMap<String, Object>> call, Throwable t) {}
        });
    }

    public void remove(int contentId) {
        ApiClient.getInstance().removeWatchLater("DELETE", deviceId(), contentId).enqueue(new Callback<HashMap<String, Object>>() {
            @Override public void onResponse(Call<HashMap<String, Object>> call, Response<HashMap<String, Object>> response) {}
            @Override public void onFailure(Call<HashMap<String, Object>> call, Throwable t) {}
        });
    }
}
