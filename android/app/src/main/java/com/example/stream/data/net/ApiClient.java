package com.example.stream.data.net;

import com.example.stream.BuildConfig;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import okhttp3.OkHttpClient;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class ApiClient {
    private static ApiService instance;

    public static ApiService getInstance() {
        if (instance == null) {
            OkHttpClient client = new OkHttpClient.Builder().build();
            Gson gson = new GsonBuilder().create();
            Retrofit retrofit = new Retrofit.Builder()
                    .baseUrl(BuildConfig.API_BASE_URL)
                    .addConverterFactory(GsonConverterFactory.create(gson))
                    .client(client)
                    .build();
            instance = retrofit.create(ApiService.class);
        }
        return instance;
    }
}
