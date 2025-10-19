package com.example.stream.data.net;

import com.example.stream.data.model.ContentItem;
import com.example.stream.data.model.Episode;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface ApiService {
    @GET("categories.php")
    Call<Map<String, Object>> getCategories();

    @GET("home.php")
    Call<Map<String, Object>> getHome();

    @GET("contents.php")
    Call<Map<String, Object>> getContents(
            @Query("type") String type,
            @Query("category_id") Integer categoryId,
            @Query("page") Integer page,
            @Query("per_page") Integer perPage
    );

    @GET("series_episodes.php")
    Call<Map<String, List<Episode>>> getSeriesEpisodes(@Query("series_id") int seriesId);

    @GET("watchlater.php")
    Call<Map<String, List<ContentItem>>> getWatchLater(@Query("device_id") String deviceId);

    @FormUrlEncoded
    @POST("watchlater.php")
    Call<HashMap<String, Object>> addWatchLater(@Field("device_id") String deviceId, @Field("content_id") int contentId);

    @FormUrlEncoded
    @POST("watchlater.php")
    Call<HashMap<String, Object>> removeWatchLater(@Field("_method") String method, @Field("device_id") String deviceId, @Field("content_id") int contentId);
}
